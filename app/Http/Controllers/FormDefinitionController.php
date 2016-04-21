<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\Field;
use CALwebtool\FormDefinition;
use CALwebtool\Group;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

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
        if ($groups->count() > 0) {
            return view('formdefinitions.create',compact('groups'));
        } else {
            Flash()->overlay("You do not hve sufficient permission to perform this action. Please contact your group's administrator.", 'Authorization Error');
            return redirect()->back();
        }
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|unique:formdefinitions|max:255',
            'description' => 'required|max:1000',
            'group'=>'required|integer',
            'definition'=>'required|array',
            'start_date'=>'required|date_format:m#d#Y',
            'end_date'=>'required|date_format:m#d#Y',
            'scores_date'=>'required|date_format:m#d#Y'
        ]);

      /* dd(['submissions_start'=> Carbon::createFromFormat("m#d#y",$request->input('start_date')),
                    'submissions_end'=> Carbon::createFromFormat("m#d#y",$request->input('end_date')),
                    'scores_due'=> Carbon::createFromFormat("m#d#y",$request->input('scores_date')),
            ]); */

        if(!(Carbon::createFromFormat("m#d#y",$request->input('start_date')) <  Carbon::createFromFormat("m#d#y",$request->input('end_date'))) || !(Carbon::createFromFormat("m#d#y",$request->input('end_date')) < Carbon::createFromFormat("m#d#y",$request->input('scores_date')))){
            return response()->json(["error"=>true,"The dates you provided are not valid"]);
        }

        $fieldErrors = new Collection();
        try {
            $formDef = new FormDefinition([
                    "name" => $request->input('name'),
                    "description" => $request->input('description'),
                    'group_id' => $request->input('group'),
                    'user_id' => Auth::user()->id,
                    'submissions_start'=> Carbon::createFromFormat("m#d#y",$request->input('start_date'))->setTime(0,0,0),
                    'submissions_end'=> Carbon::createFromFormat("m#d#y",$request->input('end_date'))->setTime(0,0,0),
                    'scores_due'=> Carbon::createFromFormat("m#d#y",$request->input('scores_date'))->setTime(0,0,0),
            ]);

            $formDef->save();

       }
        catch(\Exception $e){
            return response()->json(['message'=>"Error creating FormDefinition",'error'=>$e->getMessage()],500);
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
                    $field_options->put('options',$fieldDef->get('options'));

                    $field = new Field(['form_definition_id'=>$formDef->id,'type'=>$fieldDef->get('type'),'field_id'=>$fieldDef->get('id'),'name'=>$fieldDef->get('name'),'order'=>0,'options'=>$field_options->toJson()]);
                    $field->save();
                }
            }
            else{
                $fieldErrors->push(["Unknown field type in submission"]);
            }
        }

        if($fieldErrors->count() == 0){
            return response()->json([$formDef->id],200);
        }
        else{
            $formDef->forceDelete();
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
        if($formDef->submissions_start > Carbon::now()){
            $date = Carbon::createFromFormat('Y-m-d H:i:s',$formDef->submissions_start)->toDayDateTimeString();
            return view('formdefinitions.unstarted',compact('formDef','date'));
        }

        if($formDef->submissions_end < Carbon::now()){
            $date = Carbon::createFromFormat('Y-m-d H:i:s',$formDef->submissions_end)->toDayDateTimeString();
            return view('formdefinitions.closed',compact('formDef','date'));
        }

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

    public function edit(FormDefinition $form){
        $groups = Auth::user()->creatorGroups()->get();
            //return view('formdefinitions.create',compact('groups'));
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
            return repsonse()->json(["status" => false, "message" => "You cannot edit a form that has already opened or has submissions"]);
        }
        else{

            $fieldErrors = new Collection();
            $old_fields = $form->fields()->get();
            $new_fields = new Collection();
            $formDef = $form;

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
                else{
                    $fieldErrors->push(["Unknown field type in submission"]);
                }

                $new_fields->push($field);
            }

            if($fieldErrors->count() == 0){
                foreach($old_fields as $old_field){
                    $old_field->delete();
                }
                return response()->json([$formDef->id],200);
            }
            else{
                $formDef->forceDelete();
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

    public function destroy(){

    }
}
