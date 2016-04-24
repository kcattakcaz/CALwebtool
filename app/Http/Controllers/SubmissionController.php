<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\FormDefinition;
use CALwebtool\User;
use CALwebtool\Group;
use CALwebtool\Scores;
use CALwebtool\Submission;
use Carbon\Carbon;
use Illuminate\Http\Request;

use CALwebtool\Http\Requests;
use CALwebtool\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\DomCrawler\Form;

class SubmissionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',["except"=>['store']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $user = Auth::user();
        $user_groups = $user->groups()->get();
        $form_groups = new Collection();
        $forms = new Collection();
        foreach(Auth::user()->groups()->get() as $group){
            $forms = $forms->merge($group->formDefinitions()->get());
        }
        return view('submissions.index',compact('forms'));
    }

    public function unscored(FormDefinition $form){
        $submissions = $this->getUnscored(Auth::user(),$form);
        return view('submissions.unscored',compact('submissions','form'));
    }

    public function scored(FormDefinition $form){
        $submissions = $this->getScored(Auth::user(),$form);
        return view('submissions.scored',compact('submissions','form'));
    }

    public function completed(FormDefinition $form){
        $submissions = $this->getCompleted($form);
        return view('submissions.completed',compact('submissions','form'));
    }

    public function store(Request $request, FormDefinition $formDef){
        //dd($request->input());

        $this->validate($request,[
            'name' => 'required|max:255|string',
            'email' => 'required|email',
        ]);



        $fields = new Collection();
        $errors = new Collection();

        if($formDef->submissions()->where('email',$request->input('email'))->get()->count() > 0){
            $errors->push("A submission already exists for the e-mail you used: ". $request->input("email"));
        }


        foreach($formDef->fields()->get() as $field){
            $options = json_decode($field->options);
            if($options->required && !$request->has($field->field_id) && $field->type != 'Checkbox'){
                $errors->push("Missing required field named ".$field->name." with ID".$field->field_id);
            }
            elseif(!$request->has($field->field_id)){
                $fields->put($field->field_id,null);
            }
            else{
                if($this->verifyField($field,$request->input($field->field_id))){
                    $fields->put($field->field_id,$request->input($field->field_id));

                }
                else{
                    $errors->push("Invalid value for field named \"".$field->name."\" with ID  ".$field->field_id);
                }

              }
        }

        if($errors->count() > 0){
            //return response()->json(["The submission was REJECTED and errors follow",$errors],422);
            return redirect()->back()->withErrors($errors);
            //return view('formdefinitions.display', compact('fields', 'errors'));
        }

        $submission = new Submission(["form_definition_id"=>$formDef->id,
                                                    "name"=>$request->input('name'),
                                                    "email"=>$request->input('email'),
                                                    "password"=>null,
                                                    "submitted"=>Carbon::now(),
                                                    "status"=>'Reviewing',
                                                    "options"=>$fields->toJson()]);
        $submission->save();
        //return response()->json(["The submission was accepted and follows",$submission],200);
        return view('formdefinitions.accepted',compact('formDef','submission'));

    }

    public function getForm(FormDefinition $form){
        $submissions = $form->submissions()->get();
        return view('submissions.formIndex',compact('form','submissions'));
    }

    public function show(Submission $submissions){
        $form = $submissions->formdefinition()->first();
        $submission_fields = json_decode($submissions->options);
        $fields = new Collection();
        foreach($submission_fields as $key=>$value){
            if($form->fields()->where('field_id',$key)->get() !== null){
                $field = new Collection();
                $field->put("fieldDef",$form->fields()->where('field_id',$key)->first());
                $field->put("submission",$value);
                $fields->push($field);
            }
        }

        return view('submissions.show',compact('submissions','form','fields'));
    }

    public static function verifyField($field,$value){
        $options = json_decode($field->options);

        if($field->type == "Text"){
            $fieldArray = [$field->field_id=>$value];

            if($options->text_type == "any" || $options->text_type == "multiline"){
                $validator = Validator::make($fieldArray,[
                    $field->field_id => 'required|string',
                ]);

                if($validator->fails()){
                    return false;
                }
                else{
                    return true;
                }
            }
            else if($options->text_type == "num"){
                $validator = Validator::make($fieldArray,[
                    $field->field_id => 'required|numeric',
                ]);

                if($validator->fails()){
                    return false;
                }
                else{
                    return true;
                }
            }
            else if($options->text_type == "alpha"){
                $validator = Validator::make($fieldArray,[
                    $field->field_id => 'required|alpha',
                ]);

                if($validator->fails()){
                    return false;
                }
                else{
                    return true;
                }
            }
            else if($options->text_type == "email"){
                $validator = Validator::make($fieldArray,[
                    $field->field_id => 'required|email',
                ]);

                if($validator->fails()){
                    return false;
                }
                else{
                    return true;
                }
            }else if($options->text_type == "phone"){
                $validator = Validator::make($fieldArray,[
                    $field->field_id => 'required|string|min:10|max:40',
                ]);

                if($validator->fails()){
                    return false;
                }
                else{
                    return true;
                }
            }else if($options->text_type == "date"){
                $validator = Validator::make($fieldArray,[
                    $field->field_id => 'required|date_format:m#d#y',
                ]);

                if($validator->fails()){
                    return false;
                }
                else{
                    return true;
                }
            }else if($options->text_type == "time") {
                $validator = Validator::make($fieldArray,[
                    $field->field_id => 'required|date_format:H',
                ]);

                if($validator->fails()){
                    return false;
                }
                else{
                    return true;
                }
            }else{
                return false;
            }

        }
        elseif($field->type == "Checkbox"){
            if($value == true || $value == false){
                return true;
            }
            else{
                return false;
            }

        }
        elseif($field->type == "Select"){
            $fieldArray = [$field->field_id=>$value];
            if($options->multipleselect == true) {
                $validator = Validator::make($fieldArray, [
                    $field->field_id => 'required|array',
                    $field->field_id . ".*" => 'required|string',
                ]);
            }
            else{
                $validator = Validator::make($fieldArray, [
                    $field->field_id => 'required|string',
                    $field->field_id . ".*" => 'required|string',
                ]);
            }

            if($validator->fails()){
                return false;
            }
            else{
                return true;
            }
        }
        elseif($field->type == "Address"){
            //dd();
            //$fieldArray = [$field->field_id=$value];
            $validator = Validator::make(\Illuminate\Support\Facades\Request::input(),[
                $field->field_id."_line1" => 'required',
                $field->field_id."_line2" => 'string',
                $field->field_id."_city" => 'required|string',
                $field->field_id."_state"=>'required|string',
                $field->field_id."_country"=>'required|string',
                $field->field_id."_zip"=>'required|numeric'
            ]);

            if($validator->fails()){
                return false;
            }
            else{
                return true;
            }
        }
        elseif($field->type == "RadioGroup"){
            $fieldArray = [$field->field_id=>$value];
            $validator = Validator::make($fieldArray,[
                $field->field_id => 'required|string',
            ]);

            if($validator->fails()){
                return false;
            }
            else{
                return true;
            }
        }
        else{
            echo "<br>".$field->type;
        }
    }

    public static function reject(Submission $submissions){
        if(Auth::user()->can('reject',$submissions)) {
            $submissions->status = "Denied";
            $submissions->save();
            flash()->overlay("The submission has been rejected.","Rejected!");
            return redirect()->back();
        }
        else{
            flash()->overlay("You do not have permission to reject submissions in this team.","Not Authorized");
            return redirect()->back();
        }
    }

    public static function approve(Submission $submissions){
        if(Auth::user()->can('approve',$submissions)) {

            $submissions->status = "Approve";
            $submissions->save();

            flash()->overlay("The submission was approved","Approved!");
            return redirect()->back();
        }
        else{
            flash()->overlay("You do not have permission to approve submissions in this team.","Not Authorized");
            return redirect()->back();
        }
    }

    public static function judge(Submission $submissions){
        if(Auth::user()->can('judge',$submissions)) {

            $submissions->status = "Judged";
            $submissions->save();

            flash()->overlay("The submission was judged!","Judged!");
            return redirect()->back();
        }
        else{
            flash()->overlay("You do not have permission to approve submissions in this team.","Not Authorized");
            return redirect()->back();
        }
    }

    public static function moderate(Submission $submissions){
        if(Auth::user()->can('moderate',$submissions)) {

            $submissions->status = "Judging";
            $submissions->save();
            flash()->overlay("The judges will see this in their dashboard immediately.  They'll get an e-mail about it too","Moderated!");
            return redirect()->back();
        }
        else{
            flash()->overlay("You do not have permission to moderate submissions in this team.","Not Authorized");
            return redirect()->back();
        }
    }
    
    public static function unlock(Submission $submissions){
        if(Auth::user()->can('reject',$submissions)) {

            $submissions->status = "Reopened";
            $submissions->save();
            flash()->overlay("This feature has not been implemented.","Submission Editing Enabled");
            return redirect()->back();
        }
        else{
            flash()->overlay("You do not have permission to unlock submissions in this team.","Not Authorized");
            return redirect()->back();
        }
    }

    public static function getScored(User $user, FormDefinition $form){
        try {
            $submissions = new Collection();
            $scores = Scores::where('form_definition_id', $form->id)->where('user_id', $user->id)->get();
            foreach($scores as $score){
                $submissions->push($score->submission()->first());
            }
            return $submissions;
        }
        catch(\Exception $e){
            return new Collection();
        }
    }

    public static function getCompleted(FormDefinition $form){
        try{
            return $form->submissions()->where('status','Judged')->get();

        }
        catch(\Exception $e){
            return new Collection();
        }
    }

    public static function getUnscored(User $user, FormDefinition $form){
        try{
            $unscored = new Collection();
            $submissions = $form->submissions()->where('status','Judging')->get();
            foreach($submissions as $submission){
                if($submission->scores()->where('user_id',$user->id)->get()->count() == 0){
                    $unscored->push($submission);
                }
            }

            return $unscored;
        }
        catch(\Exception $e){
            return new Collection();
        }
    }
    
    public static function delete(Submission $submissions){
        if(Auth::user()->can('delete',$submissions)){
            $submissions->delete();
            //return response()->json(["error"=>false,"message"=>"The submission was deleted"]);
        }
        else{
            flash()->overlay("You do not have permission to delete submissions in this team.","Not Authorized");
            return redirect()->back();
            //return response()->json(["error"=>true,"message"=>"You are not authorized to delete this submission"]);
        }
    }
    
    public static function notify(){
        $forms = FormDefinition::where('status','Reviewing');
    }
}
