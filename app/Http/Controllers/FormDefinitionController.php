<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\FormDefinition;
use CALwebtool\Group;
use Illuminate\Http\Request;

use CALwebtool\Http\Requests;
use CALwebtool\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

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
        return view('formdefinitions.create',compact('groups'));
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

    public static function getSupportedFieldTypes(){

    }
}
