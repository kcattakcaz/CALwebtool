<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\Group;
use CALwebtool\User;
use Illuminate\Http\Request;

use CALwebtool\Http\Requests;
use CALwebtool\Http\Controllers\Controller;

class GroupController extends Controller
{
    //

    public function index(){
        $groups = Group::all();
        return view('groups.index',compact('groups'));
    }

    public function create(){

        $users = User::all();
        return view('groups.create',compact('users'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|unique:groups|max:255',
            'description' => 'required|max:1000',
            'initial_group_administrator' => 'required|exists:users,name'
        ]);

        $user = User::where('name',$request->input('initial_group_administrator'))->firstOrFail();
        $group = new Group(['name'=>$request->input('name'),'description'=>$request->input('description')]);
        $group->save();


        $group->users()->attach($user->id);
        $group->users()->find($user->id)->pivot->administrator = true;
        $group->save();

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
