<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\Group;
use CALwebtool\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

        $userdata = ['user'=>$user,'register_token'=>'123'];
        Mail::send('emails.user_registration',$userdata,function($m) use ($user){
            $m->to($user->email)->subject("Activate Your Account");
        });
        return redirect()->action('UserController@index');

    }

    public function show(User $user){
        return view('users.show', compact('user'));
    }

    public function update(Request $request, User $user){

        $this->validate($request,[
            'action'=>'required|in:modUser,modNotifications',

        ]);

        if($request->input('action') == 'modUser'){
            if($request->has('name')){
                $this->validate($request,[
                    'name' => 'unique:groups|max:255'
                ]);
                $user->name = $request->input('name');
            }

            if($request->has('email') && ($request->input('email') !== $user->email)){
                $this->validate($request,[
                    'email' => 'email|unique:users',
                ]);
                $user->email = $request->input('email');
            }

            if($request->has('password')){
                $this->validate($request,[
                    'password' => 'min:6|confirmed',
                ]);

                $user->password = $request->input('password');
            }

            $user->save();

            flash()->overlay("The user profile is updated.  If you changed your e-mail, you may use it for login purposes immediately, but it may take up to 48 hours to take effect for notifications.","Changes Saved");
            return redirect()->back();
        }else if($request->input('action') == 'modNotifications'){

        }
    }

    public function destroy(User $user){
        if($user == Auth::user()){
            User::destroy($user->id);
            flash()->overlay("Your profile has been deactivated","Profile Deactivated");
            Auth::logout();
            return redirect(action('HomeController@index'));
        }
        else{
            User::destroy($user->id);
            flash()->overlay("The profile has been deactivated and can no longer sign-in, but it remains in the system (so that Scores and other records remain consistent.  If you wish to completely destroy all records associated with this user, a System Administrator can forcibly delete the profile.  Likewise, if you ever need to restore access for this user, a System Administrator can re-enable the profile.","User Deactivated");
            return redirect(action('UserController@index'));
        }
    }

    public function deactivatedIndex(){
        $users = User::where('deleted_at','not',null)->get();
        return view('users.deactivated',compact('users'));
    }

    public function activate(){

    }
}
