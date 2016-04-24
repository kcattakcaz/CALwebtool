<?php

namespace CALwebtool\Http\Controllers;

use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $groups = $user->groups()->get();
        $judge = false;
        $moderator = false;
        $team_admin = false;
        $system_admin = false;

        $judge_groups = new Collection();

        foreach ($groups as $group){
            if($group->isJudge($user->id)){
                $judge_groups->push($group);
            }
        }

        $hour = Carbon::now('America/Detroit')->hour;

        if($hour > 5 && $hour < 12){
            $time = "morning";
        }
        elseif($hour < 18 && $hour >= 12){
            $time = "afternoon";
        }
        else{
            $time = "evening";
        }

        return view('dashboard.index',compact('user','groups','moderator_groups','judge_groups','admin_groups','time'));
    }

    public function settings(){
        return view ('settings.index');
    }

    public function unavailable(){
        flash()->overlay('This action is unavailable to you at this time.<br>  You may not have permission to access this
        resource or perform this action','Sorry');
        return redirect()->back();
    }
}
