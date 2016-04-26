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
        $this->middleware('auth',["except"=>["register","activate"]]);
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
            'initial_group' => 'required:array'
        ]);

        $password = str_random(40);
        $token = str_random(60);

        $user = new User(['name'=>$request->input('name'),
                            'email'=>$request->input('email'),
                            'password'=>bcrypt($password),
                            'register_token'=>$token,
                            'active'=>false,'system_admin'=>false]);
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

        $userdata = ['user'=>$user,'token'=>$token];
        Mail::queue('emails.user_registration',$userdata,function($m) use ($user){
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
                $user->active = 1;
                $user->password = bcrypt($request->input('password'));
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
        $users = User::onlyTrashed()->get();
        return view('users.deactivated',compact('users'));
    }

    public function reactivate(Request $request){
        if($request->has('user')){
            try{
                $user = User::onlyTrashed()->findOrFail($request->input('user'));
                $user->restore();
                flash()->overlay($user->name."'s profile is active again.  All previous team memberships and permissions are active as well.  The account password is the last one used, if needed you can reset it manually for the user, or they can request a password reset link from the login page.","Profile Reactivated");

            }
            catch(\Exception $e){
                flash()->overlay('The profile cannot be reactivated.  Make sure the user has not been deleted already and try again<br>'.$e->getMessage(),'Reactivation Failed');
            }
        }
        else{
            flash()->overlay('No user was specified','Reactivation Failed');
        }
       return redirect()->back();
    }

    public function forceDelete(Request $request){
        if($request->has('user')){
            try{
                $user = User::withTrashed()->findOrFail($request->input('user'));
                $user->forceDelete();
                flash()->overlay("The user and all associated data owned by the user have been deleted.  Remember, forms are owned by team, so any forms the user created will continue to exist.  Scores for judging, however, have been deleted","User Deleted");

            }
            catch(\Exception $e){
                dd($e);
                flash()->overlay('The profile cannot be deleted.  This is likely because the user owns an object that cannot be safely deleted or they are the only System Administrator.  The recommended course of action is to deactivate the profile, as this will prevent the user from signing-in, but will preserve the integrity of the system data.  If you must delete this user no matter what, you must manually inspect the database and determine why the row cannot be deleted (most likely a foreign key constraint would be violated).  You should consult the assistance of a database administrator.','Force Delete Failed');
            }
        }
        else{
            flash()->overlay('No user was specified','Reactivation Failed');
        }
        return redirect(action('UserController@index'));}

    public function activate(Request $request){
        $this->validate($request,[
           "user"=>'required|integer',
            "token"=>'required|string',
            "password"=>'required|min:6|confirmed'
        ]);
        try{
            $user = User::findOrFail( $request->input('user'));
            if($user->active){
                flash()->overlay('The user account is already activated','User Already Active');
                return redirect(action('HomeController@index'));
            }
            if($request->input('token') !== $user->register_token){
                flash()->overlay("The registration token is invalid.",'Invalid Registration Token');
                return view('users.error');
            }
            else{
                $user->password = bcrypt($request->input('password'));
                $user->register_token = str_random(60);
                $user->active = 1;
                $user->save();

                if (Auth::attempt(['email' => $user->email, 'password' => $request->input('password')])){
                    return redirect(action('HomeController@index'));
                }
                else{
                    flash()->overlay('There was a problem updating your account.  Try signing in with the password you set.  If that fails, please contact your Team Administrator','Registration Error');
                    return redirect(action('HomeController@index'));
                }


            }
        }
        catch(\Exception $e){
            flash()->overlay("The account cannot be activated at this time.  Please contact your team administrator for assistance","Activation Failed");
            return view('users.error');
        }
    }

    public function register(Request $request){
        if($request->has('user')){
            $user_id = $request->input('user');
        }
        else{
            flash()->overlay("The user account you tried to activate no longer exists","Missing User ID");
            return view('users.error');
        }

        if($request->has('token')){
            $token = $request->input('token');
        }
        else{
            flash()->overlay('You cannot activate a user account without a valid registration token.  Please contact your Team Administrator and ask them to set a password for you.','Missing Registration Token');
            return view('users.error');
        }

        try{
            $user = User::findOrFail($user_id);

            if($user->active){
                flash()->overlay('The user account has already been activated.  If you forgot your password, you can reset it from the login screen','User Already Active');
                return redirect(action("HomeController@index"));
            }

            if($user->register_token !== $token){
                flash()->overlay('The registration token is invalid.  Please contact your Team Administrator and ask them to set a password for you.','Invalid Registration Token');
                return view('users.error');
            }

        }
        catch(\Exception $e){
            flash()->overlay("The user account you tried to activate no longer exists","Invalid User ID");
            return view('users.error');
        }

        return view('users.register',compact('user','token'));
    }
}
