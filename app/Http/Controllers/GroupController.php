<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\Group;
use CALwebtool\User;
use Illuminate\Http\Request;

use CALwebtool\Http\Requests;
use CALwebtool\Http\Controllers\Controller;

class GroupController extends Controller
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
        $group->makeAdmin($user);

        return redirect()->action('GroupController@index');

    }

    public function show(Group $group){
        return view('groups.show',compact('group'));
    }

    //loads edit page
    public function edit(Group $group){
        $group_users = $group->users()->get();
        $users = User::all()->diff($group_users);

        return view('groups.edit', compact('group', 'users'));

    }

    //called by edit page
    public function update(Request $request, Group $group){

        foreach($group->users()->get() as $user) {
            if ($request->input($user->id . '-administrator') == 'on') {
                //dd(($request->input($user->id . '-administrator')) == 'on' ? 'admintrue' : 'adminfalse');
                //dd('admin');
                $group->makeAdmin($user);
            } else {
                //dd(($request->input($user->id . '-creator')) == 'on' ? 'nontrue' : 'nonfalse');
                //dd('non');
                $group->removeAdmin($user);
                $group->modifyPermissions($user, (($request->input($user->id . '-creator')) == 'on'),
                                                (($request->input($user->id . '-adjudicator')) == 'on'),
                                                (($request->input($user->id . '-moderator')) == 'on'));
            }
        }

        if ($request->input('new_group_users')) {
            foreach ($request->input('new_group_users') as $added_user) {
                try {
                    $user = User::findOrFail($added_user);
                    $group->users()->save($user);
                } catch (\Exception $e) {
                    flash()->overlay("There was a problem adding the user $added_user->name to the group. Visit the user's profile and manually check their group membership.  The error was\n $e->getMessage()", "Group Error");
                    return redirect()->action('GroupController@index');
                }
            }
        }

        return view('groups.show',compact('group'));
    }

    public function editGroupUser(Request $request, Group $group, User $user) {
        if ($request->input('administrator') == true){
            $group->users()->save($user,['administrator'=>true]);

        } elseif ($request->input('creator') == true) {
            $group->users()->save($user,['creator'=>true]);

        } elseif ($request->input('adjudicator') == true) {
            $group->users()->save($user,['adjudicator'=>true]);

        } elseif ($request->input('moderator') == true) {
            $group->users()->save($user,['moderator'=>true]);
        }

        return redirect()->action('GroupController@show', compact($group));
    }

    public function removeUser(Group $group, User $user) {
        dd($group, $user);
        $group->users()->detach($user);
        return redirect()->action('GroupController@show', compact($group));
    }

    public function destroy(){

    }
}
