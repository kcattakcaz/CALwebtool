<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\FormDefinition;
use CALwebtool\Group;
use CALwebtool\Submission;
use Carbon\Carbon;
use Illuminate\Http\Request;

use CALwebtool\Http\Requests;
use CALwebtool\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubmissionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forms = new Collection();
        foreach(Auth::user()->moderatorGroups()->get() as $group){
            $forms = $forms->merge($group->formDefinitions()->get());
        }
        return view('submissions.index',compact('forms'));
    }

    public function store(Request $request, FormDefinition $formDef){
        //dd($request->input());

        $this->validate($request,[
            'name' => 'required|max:255|string',
            'email' => 'required|email',
        ]);

        $fields = new Collection();
        $errors = new Collection();

        foreach($formDef->fields()->get() as $field){
            $options = json_decode($field->options);
            if($options->required && !$request->has($field->field_id)){
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
                    $errors->push("Invalid value for field named \"".$field->name."\" with ID: ".$field->field_id);
                }

              }
        }

        if($errors->count() > 0){
            return response()->json(["The submission was REJECTED and errors follow",$errors],422);
        }

        $submission = new Submission(["form_definition_id"=>$formDef->id,"name"=>$request->input('name'),"email"=>$request->input('email'),"password"=>null,"submitted"=>Carbon::now(),"status"=>'Reviewing',"options"=>$fields->toJson()]);
        $submission->save();
        return response()->json(["The submission was accepted and follows",$submission],200);

    }

    public function getForm(FormDefinition $form){
        $submissions = $form->submissions()->get();

        return view('submissions.formIndex',compact('form','submissions'));
    }

    public function show(Submission $submission){
        dd($submission);
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
            $validator = Validator::make($fieldArray,[
                $field->field_id => 'required|array',
                $field->field_id.".*"=>'required|string',
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

}
