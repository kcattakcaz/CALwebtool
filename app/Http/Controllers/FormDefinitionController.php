<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\Field;
use CALwebtool\FormDefinition;
use CALwebtool\Group;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
//use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use CALwebtool\User;

use CALwebtool\Http\Requests;
use CALwebtool\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\DomCrawler\Form;

class FormDefinitionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',["except"=>['displayForm']]);
    }

    public function index(){
        $forms = new Collection();
        foreach(Auth::user()->groups()->get() as $group){
            $forms = $forms->merge($group->formDefinitions()->get());
        }
        return view('formdefinitions.index',compact('forms'));
    }

    public function create(){
        
        
        
        $groups = Auth::user()->creatorGroups()->get();
        $judges = new Collection();
        foreach($groups as $group){
            $judges->put($group->id,$group->adjudicatorUsers()->get());
        }


        if ($groups->count() > 0) {
            return view('formdefinitions.create',compact('groups','judges'));
        } else {
            flash()->overlay("You do not hve sufficient permission to perform this action. Please contact your group's administrator.", 'Authorization Error');
            return redirect()->back();
        }
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|unique:formdefinitions|max:100',
            'description' => 'required',
            'group'=>'required|integer',
            'definition'=>'required|array',
            'start_date'=>'required|date_format:m#d#Y',
            'end_date'=>'required|date_format:m#d#Y',
            'scores_date'=>'required|date_format:m#d#Y',
            'sub_accept_action'=>'required|in:default,custom_message,custom_redir',
            'sub_accept_redir'=>'required_if:sub_accept_action,custom_redir|url',
            'sub_accept_content'=>'required_if:sub_accept_action,custom_message|string',
            'use_custom_css'=>'required|in:true,false',
            'custom_css_url'=>'required_if:use_custom_css,true',
            'judges'=>'required|array'
        ]);

        try{
            $group = Group::findOrFail($request->input('group'));
        }
        catch(\Exception $e){
            flash()->overlay("The team cannot be found, it may have been deleted."."Team Not Found");
        }

        if(Auth::user()->cannot('create-form',$group)){
            return response()->json(["Not authorized."=>["You do not have permission to create forms in this team"]],403);
        }
        

        if(!(Carbon::createFromFormat("m#d#y",$request->input('start_date')) <  Carbon::createFromFormat("m#d#y",$request->input('end_date'))) || !(Carbon::createFromFormat("m#d#y",$request->input('end_date')) < Carbon::createFromFormat("m#d#y",$request->input('scores_date')))){
            return response()->json(["error"=>true,"The dates you provided are not valid"]);
        }

        if($request->input('sub_accept_action') == 'default'){
            $sub_accept_content = '';
        }
        elseif($request->input('sub_accept_action') == 'custom_message'){
            $sub_accept_content = $request->input('sub_accept_content');
        }
        elseif($request->input('sub_accept_action') == 'custom_redir'){
            $sub_accept_content = $request->input('sub_accept_redir');
        }
        else{
            $sub_accept_content = '';
        }

        if($request->input('use_custom_css') == 'true'){
            $use_custom_css = true;
            $custom_css_url = $request->input("custom_css_url");
        }
        else{
            $use_custom_css = false;
            $custom_css_url = "";
        }

        $fieldErrors = new Collection();

        $judges = new Collection();

        try {
            foreach ($request->input('judges') as $judge) {
                try {
                    $judge = User::findOrFail($judge);
                    $judges->push($judge);
                }
                catch(\Exception $e){
                    //$fieldErrors->push(["Judges"=>"Judge with ID of $judge not found!"]);
                    return response()->json(["Problem with Judges: "=>["The user with ID ".$judge." cannot be found."]],422);
                }
            }
        }
        catch(\Exception $e){
            return response()->json(["Problem with Judges: "=>["There is a problem with one or more judges selected: ".$e->getMessage()]],422);
        }
        try {
            $formDef = new FormDefinition([
                    "name" => $request->input('name'),
                    "description" => $request->input('description'),
                    'group_id' => $request->input('group'),
                    'user_id' => Auth::user()->id,
                    'submissions_start'=> Carbon::createFromFormat("m#d#y",$request->input('start_date'))->setTime(0,0,0),
                    'submissions_end'=> Carbon::createFromFormat("m#d#y",$request->input('end_date'))->setTime(0,0,0),
                    'scores_due'=> Carbon::createFromFormat("m#d#y",$request->input('scores_date'))->setTime(0,0,0),
                    'notify_completed_sent'=>false,
                    'status'=>'Scheduled',
                    'sub_accept_action'=>$request->input('sub_accept_action'),
                    'sub_accept_content'=>$sub_accept_content,
                    'use_custom_css'=>$use_custom_css,
                    'custom_css_url'=>$custom_css_url
            ]);

            $formDef->save();

       }
        catch(\Exception $e){
            return response()->json(['Error Creating Form'=>["Error creating FormDefinition",$e->getMessage()]],500);
        }

        foreach($request->input('definition') as $fieldArray){
            $fieldDef = collect($fieldArray);
            $type = $fieldDef->get("type");

            if($type == "Text"){
                $validator = Validator::make($fieldArray,[
                    'id' => 'required|alpha_dash',
                    'name' => 'required',
                    'required'=>'required|boolean',
                    'text_type'=>'required|in:any,num,alpha,email,phone,date,time,multiline',
                ]);

                if($validator->fails()){
                    $fieldErrors->push($validator->errors());
                }
                else{
                    $field_options = new Collection();
                    $field_options->put('required',$fieldDef->get('required'));
                    $field_options->put('text_type',$fieldDef->get('text_type'));

                    $field = new Field(['form_definition_id'=>$formDef->id,'type'=>$fieldDef->get('type'),'field_id'=>$fieldDef->get('id'),'name'=>$fieldDef->get('name'),'order'=>0,'options'=>$field_options->toJson()]);
                    $field->save();
                }
            }
            else if($type == "Checkbox"){
                $validator = Validator::make($fieldArray,[
                    'id' => 'required|alpha_dash',
                    'name' => 'required',
                    'required'=>'required',
                    'value_checked'=>'required',
                    'value_unchecked'=>'required',
                ]);
                if($validator->fails()){
                    $fieldErrors->push($validator->errors());
                }
                else{
                    $field_options = new Collection();
                    $field_options->put('required',$fieldDef->get('required'));
                    $field_options->put('value_unchecked',$fieldDef->get('value_unchecked'));
                    $field_options->put('value_checked',$fieldDef->get('value_checked'));

                    $field = new Field(['form_definition_id'=>$formDef->id,'type'=>$fieldDef->get('type'),'field_id'=>$fieldDef->get('id'),'name'=>$fieldDef->get('name'),'order'=>0,'options'=>$field_options->toJson()]);
                    $field->save();
                }
            }
            else if($type == "Select"){
                $validator = Validator::make($fieldArray,[
                    'id' => 'required|alpha_dash',
                    'name' => 'required',
                    'required'=>'required',
                    'multipleselect'=>'required|boolean',
                    'options'=>'required|array',
                    'options.*.label'=>'required',
                    'options.*.value'=>'required',
                ]);
                if($validator->fails()){
                    $fieldErrors->push($validator->errors());
                }
                else{
                    $field_options = new Collection();
                    $field_options->put('required',$fieldDef->get('required'));
                    $field_options->put('multipleselect',$fieldDef->get('multipleselect'));
                    $field_options->put('options',$fieldDef->get('options'));

                    $field = new Field(['form_definition_id'=>$formDef->id,'type'=>$fieldDef->get('type'),'field_id'=>$fieldDef->get('id'),'name'=>$fieldDef->get('name'),'order'=>0,'options'=>$field_options->toJson()]);
                    $field->save();
                }
            }
            else if($type == "RadioGroup"){
                $validator = Validator::make($fieldArray,[
                    'id' => 'required|alpha_dash',
                    'name' => 'required',
                    'required'=>'required',
                    'options'=>'required|array',
                    'options.*.label'=>'required',
                    'options.*.value'=>'required',
                ]);
                if($validator->fails()){
                    $fieldErrors->push($validator->errors());
                }
                else{
                    $field_options = new Collection();
                    $field_options->put('required',$fieldDef->get('required'));
                    $field_options->put('options',$fieldDef->get('options'));

                    $field = new Field(['form_definition_id'=>$formDef->id,'type'=>$fieldDef->get('type'),'field_id'=>$fieldDef->get('id'),'name'=>$fieldDef->get('name'),'order'=>0,'options'=>$field_options->toJson()]);
                    $field->save();
                }
            }

            else if($type == "Address"){
                $validator = Validator::make($fieldArray,[
                    'id' => 'required|alpha_dash',
                    'name' => 'required',
                    'required'=>'required'
                ]);
                if($validator->fails()){
                    $fieldErrors->push($validator->errors());
                }
                else{
                    $field_options = new Collection();
                    $field_options->put('required',$fieldDef->get('required'));
                    //$field_options->put('options',$fieldDef->get('options'));

                    $field = new Field(['form_definition_id'=>$formDef->id,'type'=>$fieldDef->get('type'),'field_id'=>$fieldDef->get('id'),'name'=>$fieldDef->get('name'),'order'=>0,'options'=>$field_options->toJson()]);
                    $field->save();
                }
            }
            else if($type == "File"){
                $validator = Validator::make($fieldArray,[
                   'id'=>'required|alpha_dash',
                    'name'=>'required',
                    'required'=>'required',
                ]);
                if($validator->fails()){
                    $fieldErrors->push($validator->errors());
                }
                else{
                    $field_options = new Collection();
                    $field_options->put('required',$fieldDef->get('required'));
                    $field_options->put('types',$fieldDef->get('types'));

                    $field = new Field(['form_definition_id'=>$formDef->id,'type'=>$fieldDef->get('type'),'field_id'=>$fieldDef->get('id'),'name'=>$fieldDef->get('name'),'order'=>0,'options'=>$field_options->toJson()]);
                    $field->save();
                }
            }
            else{
                $fieldErrors->push(["Unknown field type in submission"]);
            }
        }

        try{
            foreach ($judges as $judge){
                $formDef->judges()->save($judge);
            }

        }catch(\Exception $e){
            $formDef->forceDelete();
            return response()->json(['Error Creating Form'=>["Failed to add a judge",$e->getMessage()]],500);
        }

        if($fieldErrors->count() == 0){
            return response()->json([$formDef->id],200);
        }
        else{
            //$formDef->forceDelete();
            $errorBag = new Collection();
            foreach($fieldErrors as $fieldError){
                foreach($fieldError->messages() as $error){
                    $errorBag->push($error);
                }
            }
            return response()->json($errorBag,422);
        }
        
        //return response()->json(true);

    }

    public function show(FormDefinition $form){

        $new_submissions = $form->submissions()->where('status','Reviewing')->get();
        $moderated_submissions = $form->submissions()->where('status','Judging')->get();
        $accepted_submissions = $form->submissions()->where('status','Approved')->get();
        $judged_submissions = $form->submissions()->where('status','Judged')->get();
        $rejected_submissions = $form->submissions()->where('status','Denied')->get();
        return view('formdefinitions.show', compact('form','new_submissions','moderated_submissions','accepted_submissions','rejected_submissions','judged_submissions'));
    }



    public function displayForm(FormDefinition $formDef)
    {
        //if($formDef->submissions_start > Carbon::now()){

        if($formDef->status == "Scheduled" || $formDef->status == "Drafting"){
            $date = Carbon::createFromFormat('Y-m-d H:i:s',$formDef->submissions_start)->toDayDateTimeString();
            return view('formdefinitions.unstarted',compact('formDef','date'));
        }
        elseif($formDef->status == "Accepting"){
            $fields = new Collection();
            foreach ($formDef->fields()->get() as $fieldDef) {
                $field = new Collection();
                $field->put('type', $fieldDef->type);
                $field->put('id', $fieldDef->field_id);
                $field->put('name', $fieldDef->name);
                $field->put('options', new Collection(json_decode($fieldDef->options)));
                $fields->push($field);
            }
            return view('formdefinitions.display', compact('fields', 'formDef'));
        }

        //if($formDef->submissions_end < Carbon::now()){
        else{
            $date = Carbon::createFromFormat('Y-m-d H:i:s',$formDef->submissions_end)->toDayDateTimeString();
            return view('formdefinitions.closed',compact('formDef','date'));
        }



    }

    public function edit(FormDefinition $form){
        $groups = Auth::user()->creatorGroups()->get();
            //return view('formdefinitions.create',compact('groups'));

        if($form->submissions()->get()->count() > 0){
            flash()->overlay("You cannot edit a form after it has opened and received submissions","Form Edit");
            return redirect()->back();
        }

        return view('formdefinitions.edit',compact('form','groups'));
    }

    public static function getDefinition($form){
        $formDef= new Collection();
        foreach($form->fields()->get() as $field){
            $fieldDef = new Collection();
            $fieldDef->put("id",$field->field_id);
            $fieldDef->put("type",$field->type);
            $fieldDef->put("name",$field->name);
            $fieldDef->put("order",$field->order);
            $fieldDef->put("options",$field->options);
            $formDef->push($fieldDef);
        }
        return $formDef->toJson();

    }

    public function update(FormDefinition $form, Request $request){
        if($form->status != "Drafting" || $form->submissions()->get()->count() > 0 ) {
            return response()->json(["error" => true, "message" => ["You cannot edit a form that isn't a Draft or has submissions"]],405);
        }
        else{

            $this->validate($request,[
                'name' => 'required|max:100',
                'description' => 'required',
                'definition'=>'required|array']);

            $fieldErrors = new Collection();
            $old_fields = $form->fields()->get();
            $new_fields = new Collection();
            $formDef = $form;

            $formDef->name= $request->input("name");
            $formDef->description = $request->input('description');

            foreach($request->input('definition') as $fieldArray){
                $fieldDef = collect($fieldArray);
                $type = $fieldDef->get("type");

                if($type == "Text"){
                    $validator = Validator::make($fieldArray,[
                        'id' => 'required|alpha_dash',
                        'name' => 'required',
                        'required'=>'required|boolean',
                        'text_type'=>'required|in:any,num,alpha,email,phone,date,time,multiline',
                    ]);

                    if($validator->fails()){
                        $fieldErrors->push($validator->errors());
                    }
                    else{
                        $field_options = new Collection();
                        $field_options->put('required',$fieldDef->get('required'));
                        $field_options->put('text_type',$fieldDef->get('text_type'));

                        $field = new Field(['form_definition_id'=>$formDef->id,'type'=>$fieldDef->get('type'),'field_id'=>$fieldDef->get('id'),'name'=>$fieldDef->get('name'),'order'=>0,'options'=>$field_options->toJson()]);
                        $field->save();
                    }
                }
                else if($type == "Checkbox"){
                    $validator = Validator::make($fieldArray,[
                        'id' => 'required|alpha_dash',
                        'name' => 'required',
                        'required'=>'required',
                        'value_checked'=>'required',
                        'value_unchecked'=>'required',
                    ]);
                    if($validator->fails()){
                        $fieldErrors->push($validator->errors());
                    }
                    else{
                        $field_options = new Collection();
                        $field_options->put('required',$fieldDef->get('required'));
                        $field_options->put('value_unchecked',$fieldDef->get('value_unchecked'));
                        $field_options->put('value_checked',$fieldDef->get('value_checked'));

                        $field = new Field(['form_definition_id'=>$formDef->id,'type'=>$fieldDef->get('type'),'field_id'=>$fieldDef->get('id'),'name'=>$fieldDef->get('name'),'order'=>0,'options'=>$field_options->toJson()]);
                        $field->save();
                    }
                }
                else if($type == "Select"){
                    $validator = Validator::make($fieldArray,[
                        'id' => 'required|alpha_dash',
                        'name' => 'required',
                        'required'=>'required',
                        'multipleselect'=>'required|boolean',
                        'options'=>'required|array',
                        'options.*.label'=>'required',
                        'options.*.value'=>'required',
                    ]);
                    if($validator->fails()){
                        $fieldErrors->push($validator->errors());
                    }
                    else{
                        $field_options = new Collection();
                        $field_options->put('required',$fieldDef->get('required'));
                        $field_options->put('multipleselect',$fieldDef->get('multipleselect'));
                        $field_options->put('options',$fieldDef->get('options'));

                        $field = new Field(['form_definition_id'=>$formDef->id,'type'=>$fieldDef->get('type'),'field_id'=>$fieldDef->get('id'),'name'=>$fieldDef->get('name'),'order'=>0,'options'=>$field_options->toJson()]);
                        $field->save();
                    }
                }
                else if($type == "RadioGroup"){
                    $validator = Validator::make($fieldArray,[
                        'id' => 'required|alpha_dash',
                        'name' => 'required',
                        'required'=>'required',
                        'options'=>'required|array',
                        'options.*.label'=>'required',
                        'options.*.value'=>'required',
                    ]);
                    if($validator->fails()){
                        $fieldErrors->push($validator->errors());
                    }
                    else{
                        $field_options = new Collection();
                        $field_options->put('required',$fieldDef->get('required'));
                        $field_options->put('options',$fieldDef->get('options'));

                        $field = new Field(['form_definition_id'=>$formDef->id,'type'=>$fieldDef->get('type'),'field_id'=>$fieldDef->get('id'),'name'=>$fieldDef->get('name'),'order'=>0,'options'=>$field_options->toJson()]);
                        $field->save();
                    }
                }
                else if($type == "Address"){
                    $validator = Validator::make($fieldArray,[
                        'id' => 'required|alpha_dash',
                        'name' => 'required',
                        'required'=>'required'
                    ]);
                    if($validator->fails()){
                        $fieldErrors->push($validator->errors());
                    }
                    else{
                        $field_options = new Collection();
                        $field_options->put('required',$fieldDef->get('required'));
                        $field_options->put('options',$fieldDef->get('options'));

                        $field = new Field(['form_definition_id'=>$formDef->id,'type'=>$fieldDef->get('type'),'field_id'=>$fieldDef->get('id'),'name'=>$fieldDef->get('name'),'order'=>0,'options'=>$field_options->toJson()]);
                        $field->save();
                    }
                }
                else if($type == "File"){
                    $validator = Validator::make($fieldArray,[
                        'id'=>'required|alpha_dash',
                        'name'=>'required',
                        'required'=>'required',
                    ]);
                    if($validator->fails()){
                        $fieldErrors->push($validator->errors());
                    }
                    else{
                        $field_options = new Collection();
                        $field_options->put('required',$fieldDef->get('required'));
                        $field_options->put('types',$fieldDef->get('types'));

                        $field = new Field(['form_definition_id'=>$formDef->id,'type'=>$fieldDef->get('type'),'field_id'=>$fieldDef->get('id'),'name'=>$fieldDef->get('name'),'order'=>0,'options'=>$field_options->toJson()]);
                        $field->save();
                    }
                }
                else{
                    $fieldErrors->push(["Unknown field type in submission"]);
                    continue;
                }

                $new_fields->push($field);
            }

            if($fieldErrors->count() == 0){
                foreach($old_fields as $old_field){
                    $old_field->delete();
                }
                $formDef->save();
                return response()->json(["id"=>$formDef->id,"new_fields"=>$new_fields,"old_fields"=>$old_fields],200);
            }
            else{
                //$formDef->forceDelete();
                $errorBag = new Collection();
                foreach($fieldErrors as $fieldError){
                    foreach($fieldError->messages() as $error){
                        $errorBag->push($error);
                    }
                }
                return response()->json($errorBag,422);
            }

        }
    }


    public function schedule(FormDefinition $form, Request $request){
        if($form->status == "Archived" ) {
            flash()->overlay("It is not possible to modify an archived form.","Status Change Failed");
            return redirect()->back();
        }
        return view('formdefinitions.schedule',compact('form'));
    }


    public function updateSchedule(FormDefinition $form, Request $request){
        $this->validate($request,[
            'start_date'=>'required|date_format:m#d#Y',
            'end_date'=>'required|date_format:m#d#Y',
            'scores_date'=>'required|date_format:m#d#Y']
        );

    }

    public function updateStatus(FormDefinition $form, Request $request){
        $this->validate($request,[
                'status'=>'required|in:Drafting,Scheduled,Accepting,Reviewing,Scored,Archived'
            ]
        );

        if($form->submissions()->get()->count() > 0 && ($request->input("status") == "Drafting" || $request->input("status") == "Scheduled")) {
            flash()->overlay("This form already has submissions.  It is not possible to change to Draft or Scheduled status at this point, as these would permit modifying the form definition, and that would corrupt all submissions","Status Change Failed");
        }
        if($form->status == "Archived"){
            flash()->overlay("It is not possible to modify the status of an archived form.","Status Change Failed");
        }
        else{
            try{
                $form->status = $request->input("status");
                $form->save();
                flash()->overlay("The form status has been updated.","Status Changed");
            }
            catch(\Exception $e){
                flash()->overlay("The status cannot be changed due to a technical problem.","Status Change Failed");
            }

        }

        return redirect(action('FormDefinitionController@show',compact('form')));
    }

    public function delete(Request $request){
        if($request->has('form')){
            try{
                $form = FormDefinition::findOrFail($request->input('form'));
            }
            catch(\Exception $e){
                flash()->overlay("The form you specified couldn't be found","Form Not Found");
                return redirect()->back();
            }
        }
        else{
            flash()->overlay("You did not specify a form to delete.","No Form Specified");
            return redirect()->back();
        }
        flash()->overlay("<form action='".action('FormDefinitionController@destroy',compact('form'))."' method='post'>". csrf_field(). "<input type='hidden' name='_method' value='DELETE'> <strong>Are you sure you want to delete this form?  All associated data including form defintion, submissions, and scores will be destroyed.</strong> <br><input class='btn btn-danger' type='submit' value='Yes, I want to DELETE this form'></form>","Are you sure?");
        return redirect()->back();
    }

    public function destroy(FormDefinition $form){

        try {
            $form->delete();
            flash()->overlay("The form has been deleted.","Form Deleted");
            return redirect(action('FormDefinitionController@index'));
        }
        catch(\Exception $e){
            flash()->overlay("The form cannot be deleted.","Form Not Deleted");
            return redirect()->back();
        }

    }

    public function judges(FormDefinition $form){
        $current_judges = $form->judges()->get();
        $available_judges = $form->group()->first()->adjudicatorUsers()->get()->diff($current_judges);

        return view('formdefinitions.judges',compact('form','current_judges','available_judges'));
    }

    public function updateJudges(FormDefinition $form, Request $request){
        $this->validate($request, [
           'judges' => 'required|array',
            'judges.*'=>'exists:users,id'
        ]);

        $error_list = new Collection();

        $updated_judges = new Collection();

        foreach($request->input('judges') as $new_judge_id) {
            try {
                $judge = $form->group()->first()->adjudicatorUsers()->findOrFail($new_judge_id);
                $updated_judges->put($judge->id,$judge);
            } catch (\Exception $e) {
                $error_list->push("Judge with User ID of " . $new_judge_id . " not found and thus not added!");

            }
        }

        $current_judges = $form->judges()->get();

        foreach($current_judges as $cur_judge){
            if(!$updated_judges->has($cur_judge->id)){
                $form->judges()->detach($cur_judge->id);
            }
            $updated_judges->forget($cur_judge->id);
        }

        foreach ($updated_judges as $new_judge){
            $form->judges()->save($new_judge);
        }

        if($error_list->count() > 0){
            $error_string = "";
            foreach($error_list as $error){
                $error_string = $error_string . "<br>$error";
            }
            flash()->overlay("Something went wrong, and not all the judges may have been added correctly.  Double check the results.".$error_string,"Error");
            return redirect()->back();
        }
        else{
            flash()->overlay("The judges have been added to the form","Form Updated");
            return redirect()->back();
        }
    }

    public static function scheduleForms(){
        $forms = FormDefinition::where('status','!=','archived')->get();
        $time = Carbon::now('America/Detroit');

        foreach($forms as $form){
            echo "<hr>";
            echo "TIME NOW: $time <br>";
            echo "Form Start: ".$form->submissions_start."<br>";
            echo "Form Stop: ".$form->submissions_end."<br>";
            echo "Scores Due: ".$form->scores_due."<br>";
            if($form->status == 'Drafting'){
                continue;
            }
            if($form->submissions_start < $time){
                echo "Form needs to open!<br>";
                $form->status = "Accepting";
            }
            if($form->submissions_end < $time){
                echo "Form needs to close!<br>";
                $form->status = "Reviewing";
            }
            if($form->scores_due < $time){
                echo "Form needs to complete<br>";
                $form->status = "Scored";
            }

            echo "<br>";
            echo $form->name. "  status is now: ". $form->status;
            echo "<br>";
            echo "<br>";
            echo "<hr>";

            $form->save();
        }
    }

}
