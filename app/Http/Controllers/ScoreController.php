<?php

namespace CALwebtool\Http\Controllers;

use Illuminate\Http\Request;

use CALwebtool\Http\Requests;
use CALwebtool\Http\Controllers\Controller;
use CALwebtool\Submission;

class ScoreController extends Controller
{
    //
    
    public function index(){
        
    }

    public function create(Submission $submissions){

        return view('scores.create',compact('submissions'));
    }
    
    public function store(){
        
    }
    
    public function update(){
        
    }
}
