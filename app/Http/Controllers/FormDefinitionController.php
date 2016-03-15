<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\FormDefinition;
use Illuminate\Http\Request;

use CALwebtool\Http\Requests;
use CALwebtool\Http\Controllers\Controller;

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
        $groups = FormDefinition::all();
        return view('groups.index',compact('groups'));
    }

    public function create(){

        return view('groups.create');
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|unique:formdefinitions|max:255',
            'description' => 'required|max:1000',
        ]);


        return redirect()->action('GroupController@index');

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
