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
        $group->users()->save($user,['administrator'=>true]);

        return redirect()->action('GroupController@index');

    }

    public function show(Group $group){
        return view('groups.show',compact('group'));
    }

    //loads edit page
    public function edit(Group $group){
        $group_users = array($group->users()->get());               //these need to send
        $users = array_diff(array(User::all()) , $group_users);    //the correct ata type

        echo $users;
        //return view('groups.edit', compact(['group', 'users']));    //right meow i think it's a group and a user

    }

    //called by edit page
    public function update(Request $request, Group $group){

        $this->validate($request,[
            'name' => 'required|unique:groups|max:255',
            'description' => 'required|max:1000',
        ]);

        foreach($request->input('group_users') as $added_user){
            try {
                $user = User::findOrFail($added_user);
                $group->users()->save($user);
            }
            catch(\Exception $e){
                flash()->overlay("The user was created successfully, but there was a problem adding this user to the group with ID $init_group. Visit the user's profile and manually check their group membership.  The error was\n $e->getMessage()","Group Error");
                return redirect()->action('GroupController@show');
            }
        }


        return view('groups.show',compact('group'));
    }

    public function destroy(){

    }
}
