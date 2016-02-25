<?php

namespace CALwebtool\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

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
        return view('home');
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
