<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\FormDefinition;
use CALwebtool\Group;
use Illuminate\Http\Request;

use CALwebtool\Http\Requests;
use CALwebtool\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
        $this->middleware('auth');
    }

    public function index(){
        $groups = new Collection();
        return view('formdefinitions.index',compact('groups'));
    }

    public function create(){
        $groups = Auth::user()->creatorGroups()->get();
        $field_types = self::getSupportedFieldTypes();
        return view('formdefinitions.create',compact('groups','field_types'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|unique:formdefinitions|max:255',
            'description' => 'required|max:1000',
        ]);


        return redirect()->action('FormDefinitionController@index');

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

    public static function textField(){
        $field = new Collection();
        $options = array("Required"=>"Boolean","MultiLine"=>"Boolean","MaxLength"=>"Integer","MinLength"=>"Integer","EMail"=>"Boolean");
        $html_options = "<div class='panel panel-default'><div class='panel-heading'><h3 class='panel-title'>Panel title</h3></div><div class='panel-body'>Panel content</div></div>";
        $name = "Text Field";

        $field->put("options",$options);
        $field->put("html_options",$html_options);
        $field->put("name",$name);

        return $field;
    }

    public static function checkboxField(){
        $field = new Collection();
        $options = array("Required"=>"Boolean");
        $html_options = "<p>CheckboxField</p>";
        $name = "Checkbox";

        $field->put("options",$options);
        $field->put("html_options",$html_options);
        $field->put("name",$name);

        return $field;
    }

    public static function getSupportedFieldTypes(){
        //return ['Text','Checkbox','Radios','Select','File'];
        $fields = new Collection();
        $fields->put("Text",FormDefinitionController::textField());
        $fields->put("CheckBox",FormDefinitionController::checkboxField());
        return $fields;
    }
}
