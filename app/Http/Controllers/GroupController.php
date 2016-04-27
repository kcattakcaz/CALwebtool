<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\Group;
use CALwebtool\User;
use Illuminate\Http\Request;

use CALwebtool\Http\Requests;
use CALwebtool\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


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
        if(Auth::user()->isSystemAdmin()){
            $groups = Group::all();
        }
        else{
            $groups = Auth::user()->adminGroups()->get();
        }

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
        $group_users = $group->users()->get();
        $users = User::all()->diff($group_users);
        return view('groups.edit', compact('group', 'users'));
        //return view('groups.show',compact('group'));
    }

    //loads edit page
    public function edit(Group $group){
        $group_users = $group->users()->get();
        $users = User::all()->diff($group_users);
        return view('groups.edit', compact('group', 'users'));
    }

    //called by edit page
    public function update(Request $request, Group $group){

        $this->validate($request,[
            "action"=>"required|in:updateName,updateDescription,addMember,delMember,modMember,promoteAdmin,demoteAdmin",
            "name"=>"string|max:255|requiredIf:action,updateName",
            "description"=>"max:1000|requiredIf:action,updateDescription",
            "user_id"=>'numeric',
        ]);

        if($request->has('creator')){
            $creator = true;
        }
        else {
            $creator = false;
        }
        if($request->has('moderator')){
            $moderator = true;
        }
        else {
            $moderator = false;
        }
        if($request->has('adjudicator')){
            $adjudicator = true;
        }
        else {
            $adjudicator = false;
        }
        if($request->has('administrator')){
            $administrator = true;
            $adjudicator = true;
            $moderator = true;
            $creator = true;
        }
        else{
            $administrator = false;
        }


        if($request->input("action") == "updateName"){
            $group->name = $request->input("name");
            $group->save();
            return redirect()->back();
        }
        elseif($request->input("action") == "updateDescription"){
            $group->description = $request->input("description");
            $group->save();
            return redirect()->back();
        }
        elseif($request->input("action") == "addMember") {
            try {
                $user = User::findOrFail($request->input("user_id"));
            }
            catch(\Exception $e){
                flash()->overlay("The user couldn't be found, the account may have been deleted recently","User Not Found");
                return redirect()->back();
            }
            if($group->users()->get()->contains($user)){
                flash()->overlay("The user already exists in this group","User Exists");
                return redirect()->back();
            }
            else{
                if($administrator){
                    $group->makeAdmin($user);
                }
                else{
                    $group->addUser($user,$creator,$moderator,$adjudicator);
                    $group->modifyPermissions($user,$creator,$moderator,$adjudicator);
                }

                flash()->overlay($user->name." (".$user->email.") is now a member of this team.","Team Member Added");
                return redirect()->back();
            }
        }
        elseif($request->input("action") == "delMember"){
            $user = User::find($request->input("user_id"));

            if($group->users()->get()->contains($user)){
                //$group->delUser($user);
                $group->users()->detach([$user->id]);
                flash()->overlay("The user was removed from the team","User Removed");
                return redirect()->back();
            }
            else{
                flash()->overlay("The user you requested to delete, does not exist in the team, the user may already have been deleted","User Deleted");
                return redirect()->back();
            }

        }
        elseif($request->input("action") == "modMember"){
            try {
                $user = User::findOrFail($request->input("user_id"));
            }
            catch(\Exception $e){
                flash()->overlay("The user couldn't be found or no longer exists<br>User ID was ".$request->input('user_id'),"User Not Found");
                return redirect()->back();
            }
            //dd($request->input());
            if($administrator){
                $group->makeAdmin($user);
            }
            else{
                if($group->isAdmin($user->id)){
                    $group->removeAdmin($user);
                }
                $group->modifyPermissions($user,$creator,$moderator,$adjudicator);
            }

            flash()->overlay($user->name." (".$user->email.") now has the permissions<br><br>Administrator: ".($administrator ? "True" : "False")."<br>Creator: ".($creator ? "True" : "False")."<br>Moderator: ".($moderator ? "True" : "False")."<br> Judge: ".($adjudicator ? "True" : "False"),"Permissions Updated");
            return redirect()->back();
        }
        elseif($request->input("action") == "promoteAdmin"){
            flash()->overlay("Do not use this","Do not use!");
            return redirect()->back();

        }
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

    /*-public function removeUser(Group $group, User $user) {
        //$group->users()->detach([$user->id]);
        try{
            $group->users()->detach([$user->id]);
            flash()->overlay("The user was removed from the group","User Removed");
            return redirect()->back();
        }
        catch(\Exception $e){
            flash()->overlay("There was a problem removing the user from the group, please restart your Internet browser and try again.<br>".$e->getMessage(),"User Removal Incomplete");
            return redirect()->back();
        }
    } */

    public function destroy(){


        //return redirect()->action('GroupController@show', compact($group));
    }
}
