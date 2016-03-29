<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\Field;
use CALwebtool\FormDefinition;
use CALwebtool\Group;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use CALwebtool\Http\Requests;
use CALwebtool\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FormDefinitionController extends Controller
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

    public function index(){
        $forms = new Collection();
        foreach(Auth::user()->groups()->get() as $group){
            $forms = $forms->merge($group->formDefinitions()->get());
        }
        return view('formdefinitions.index',compact('forms'));
    }

    public function create(){
        $groups = Auth::user()->creatorGroups()->get();
        return view('formdefinitions.create',compact('groups'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|unique:formdefinitions|max:255',
            'description' => 'required|max:1000',
            'group'=>'required|integer',
            'definition'=>'required|array',
        ]);


        $fieldErrors = new Collection();
        try {
            $formDef = new FormDefinition(["name" => $request->input('name'), "description" => $request->input('description'), 'group_id' => $request->input('group'), 'user_id' => Auth::user()->id]);
            $formDef->save();
        }
        catch(QueryException $e){
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
                    'multiline'=>'required|boolean',
                    'maxlength'=>'required|integer',
                    'minlength'=>'required|integer'
                ]);

                if($validator->fails()){
                    $fieldErrors->push($validator->errors());
                }
                else{
                    $field_options = new Collection();
                    $field_options->put('required',$fieldDef->get('required'));
                    $field_options->put('multiline',$fieldDef->get('multiline'));
                    $field_options->put('maxlength',$fieldDef->get('maxlength'));
                    $field_options->put('minlength',$fieldDef->get('minlength'));

                    $field = new Field(['formdefinition_id'=>$formDef->id,'type'=>$fieldDef->get('type'),'field_id'=>$fieldDef->get('id'),'name'=>$fieldDef->get('name'),'order'=>0,'options'=>$field_options->toJson()]);
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

                    $field = new Field(['formdefinition_id'=>$formDef->id,'type'=>$fieldDef->get('type'),'field_id'=>$fieldDef->get('id'),'name'=>$fieldDef->get('name'),'order'=>0,'options'=>$field_options->toJson()]);
                    $field->save();
                }
            }
            else{
                $fieldErrors->push(["Unknown field type in submission"]);
            }
        }

        if($fieldErrors->count() == 0){
            return response()->json([true],200);
        }
        else{
            $formDef->forceDelete();
            return response()->json($fieldErrors,422);
        }


        //return response()->json(true);

    }

    public function show(Group $group){
        return view('groups.show',compact('group'));
    }

    public function edit(){

    }

    public function update(){

    }

    public function destroy(){

    }
}
