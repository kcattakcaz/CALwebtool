<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\Group;
use CALwebtool\User;
use Illuminate\Http\Request;

use CALwebtool\Http\Requests;
use CALwebtool\Http\Controllers\Controller;

class UserController extends Controller
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
        $users = User::all();
        return view('users.index',compact('users'));
    }

    public function create(){

        $groups = Group::all();
        return view('users.create',compact('groups'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|unique:groups|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'initial_group' => 'required:array'
        ]);


        $user = new User(['name'=>$request->input('name'),
                            'email'=>$request->input('email'),
                            'password'=>bcrypt($request->input('password')),
                            'active'=>true,'system_admin'=>false]);
        $user->save();

        foreach($request->input('initial_group') as $init_group){
            try {
                $group = Group::findOrFail($init_group);
                $group->users()->save($user);
            }
            catch(\Exception $e){
                flash()->overlay("The user was created successfully, but there was a problem adding this user to the group with ID $init_group. Visit the user's profile and manually check their group membership.  The error was\n $e->getMessage()","Group Error");
                return redirect()->action('UserController@index');
            }
        }

        return redirect()->action('UserController@index');

    }

    public function show(User $user){
        return view('users.show', compact('user'));
    }

    public function update(Request $request){

        $this->validate($request,[
            'name' => 'unique:groups|max:255',
            'email' => 'email|unique:users',
            'password' => 'min:6|confirmed',
        ]);

        //$user->name = $request->input('name');
        //$user->name = $request->input('email');
        //$user->name = $request->input('password');
        //$user->save();
    }

    public function destroy(){

    }
}
