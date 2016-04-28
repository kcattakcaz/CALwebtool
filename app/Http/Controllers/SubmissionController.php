<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\Field;
use CALwebtool\FormDefinition;
use CALwebtool\User;
use CALwebtool\Group;
use CALwebtool\Scores;
use CALwebtool\Submission;
use Carbon\Carbon;
use Illuminate\Http\Request;

use CALwebtool\Http\Requests;
use CALwebtool\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\DomCrawler\Form;
use Illuminate\Support\Facades\Mail;

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

    public function retrieveFile(Submission $submissions, $file){
        $form = $submissions->formdefinition()->first();
        if($submissions->group()->users()->get()->contains(Auth::user())){
            //$file = Storage::get("form/".$form->id."/".$file);
            $filepath = "form/".$form->id."/".$file;
            //Storage::get(form/)
           // if(Storage::exists($filepath)){

               /* if(copy($filepath,"/var/www/calwebtool/public/downloads/".$file)){
                    return respones()->file("downloads/".$file);
                }
                //Storage::copy($filepath,"downloads/".$file);
                return response()->file("downloads/".$file);
            //}
            /*else{
                flash()->overlay("The file does not exist.","Not Found");
                return redirect()->back();
            }*/
            if(Storage::exists($filepath)){
                if(Storage::exists("downloads/".$file)){
                    Storage::delete("downloads/".$file);
                }
                Storage::copy($filepath,"downloads/".$file);
                return response()->download("downloads/".$file);
            }

        }
    }

    public function store(Request $request, FormDefinition $formDef){
        $this->validate($request,[
            'name' => 'required|max:255|string',
            'email' => 'required|email',
        ]);



        $fields = new Collection();
        $errors = new Collection();
        $request->session()->forget('field_validation_errors');
        $request->session()->put('field_validation_errors',[]);

        if($formDef->submissions()->where('email',$request->input('email'))->get()->count() > 0){
            $errors->push("A submission already exists for the e-mail you used: ". $request->input("email"));
        }


        foreach($formDef->fields()->get() as $field){
            $options = json_decode($field->options);
            if($options->required && (!$request->has($field->field_id)) && ($field->type != 'Checkbox' && $field->type != 'File')){
                $errors->push("Missing required field named ".$field->name." with ID ".$field->field_id);
            }
            elseif($options->required && $field->type == "File" && !$request->hasFile($field->field_id)){
                $errors->push("Missing required file field named ".$field->name." with ID ".$field->field_id);
            }
            elseif(!$request->has($field->field_id) && $field->type != 'File'){
                $fields->put($field->field_id,null);
            }
            else{
                if ($this->verifyField($field, $request->input($field->field_id), $request)) {
                    if($field->type != 'File') {
                        $fields->put($field->field_id, $request->input($field->field_id));
                    }
                    else{
                        $fname ="file_".str_random(32).".".$request->file($field->field_id)->getClientOriginalExtension();
                        $file =$request->file($field->field_id)->move('../storage/app/form/'. $formDef->id,$fname);
                        $fields->put($field->field_id,$fname);
                    }

                } else {
                    $errors->push("Invalid value for field named \"" . $field->name . "\" with ID  " . $field->field_id);
                }
                }
        }

        if($errors->count() > 0){
            //return response()->json(["The submission was REJECTED and errors follow",$errors],422);
            return redirect()->back()->withErrors($errors);
            //return view('formdefinitions.display', compact('fields', 'errors'));
        }

        $request->session()->forget('form_validation_errors');

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
        $group = $form->group()->first();
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

        return view('submissions.show',compact('submissions','form','fields','group'));
    }

    public static function verifyField($field,$value,$request){
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
        elseif($field->type == "File"){

            if($request->file($field->field_id)->isValid()){
                $type = $request->file($field->field_id)->getMimeType();
                return true;
            }
            else{
                Session::push('field_validation_errors',$field->name." file field failed to upload successfully or was of an invalid file type");
                return false;
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

    public static function appendErrors(\Illuminate\Validation\Validator $validator){
        foreach($validator->errors()->all() as $message){
            Session::push('field_validation_errors',$message);
        }
    }

    public function rejectNotify(Submission $submissions){
        if(Auth::user()->can('reject',$submissions)){
            flash()->overlay(view('submissions.message',compact('submissions'))->render(),"Reject with Message");
            return redirect()->back();
        }
    }

    public static function finalRejection(Submission $submissions){
        if(Auth::user()->can('reject',$submissions)){
            flash()->overlay(view('submissions.reject',compact('submissions'))->render(),"Reject Submission");
            return redirect()->back();
        }
        else{
            flash()->overlay("You don't have permission to reject submissions in this form","Not Authorized");
            return redirect()->back();
        }
    }

    public function sendRejectNotify(Submission $submissions, Request $request){
        $this->validate($request,[
            'recipient'=>'required|email',
            'subject'=>'required|string|max:40',
            'message'=>'required|string'
        ]);
        if(Auth::user()->can('reject',$submissions)){

            $user= Auth::user();
            $subject = $request->input('subject');
            $recipient = $request->input('recipient');

            Mail::queue('emails.submission_rejection', ["content"=>$request->input('message')], function ($message) use ($recipient,$user,$subject,$submissions){
                $message->from($user->email,$user->name);
                $message->subject($subject);
                $message->to($recipient,$submissions->name);
            });
            
            $submissions->status = "Denied";
            $submissions->save();

            flash()->overlay("The submission was rejected, and the message sent.",'Rejected and Notified');
            return redirect()->back();
        }
        else{
            flash()->overlay("You do not have permission to reject submissions in this team","Not Authorized");
            return redirect()->back();
        }
    }

    public function accept(Submission $submissions){
        if(Auth::user()->can('approve',$submissions)){
            flash()->overlay(view('submissions.deny',compact('submissions'))->render(),"Approve Submission");
            return redirect()->back();
        }
        else{
            flash()->overlay("You do not have permission to approve submissions in this team",'Not Authorized');
            return redirect()->back();
        }
    }


    public function approve(Submission $submissions, Request $request){
        $this->validate($request,
            [
                'message' => 'required|string'
            ]);

        if(Auth::user()->can('approve',$submissions)) {

            $submissions->status = "Approved";
            $submissions->judgement = $request->input("message");
            $submissions->save();

            flash()->overlay("The submission was approved","Approved!");
            return redirect()->back();
        }
        else{
            flash()->overlay("You do not have permission to approve submissions in this team.","Not Authorized");
            return redirect()->back();
        }
    }

    public function deny(Submission $submissions, Request $request){
        $this->validate($request,
            [
                'message' => 'required|string'
            ]);

        if(Auth::user()->can('reject',$submissions)) {

            $submissions->status = "Denied";
            $submissions->judgement = $request->input("message");
            $submissions->save();
            $user = Auth::user();
            $form = $submissions->formdefinition()->first();
            foreach($submissions->group()->administratorUsers()->get() as $admin){
                Mail::queue('emails.submission_final_rejection', ["content"=>$request->input('message'),"submission"=>$submissions,"admin"=>$admin,"form"=>$form], function ($message) use ($user,$admin){
                    $message->from($user->email,$user->name);
                    $message->subject("Submission Rejected");
                    $message->to($admin->email,$admin->name);
                });
            }

            flash()->overlay("The submission was denied","Denied!");
            return redirect()->back();
        }
        else{
            flash()->overlay("You do not have permission to deny submissions in this team.","Not Authorized");
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
    
    public function trash(Submission $submissions){
        if(Auth::user()->can('delete',$submissions)){
            $form = $submissions->formdefinition()->first();
            $action = action("FormDefinitionController@show",compact('form'));
            $submissions->delete();
            flash()->overlay("The submission has been deleted.","Submission Deleted");
            return redirect($action);
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
