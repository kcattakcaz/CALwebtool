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
        ]);

        $user = new User(['name'=>$request->input('name'),'email'=>$request->input('email'),'password'=>bcrypt($request->input('password')),'active'=>true,'system_admin'=>false]);
        $user->save();

        return redirect()->action('UserController@index');

    }

    public function show(User $user){
        return view('users.show',compact('user'));
    }

    public function edit(){

    }

    public function update(){

    }

    public function destroy(){

    }
}
