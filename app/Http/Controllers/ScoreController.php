<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\Scores;
use Carbon\Carbon;
use Illuminate\Http\Request;

use CALwebtool\Http\Requests;
use CALwebtool\Http\Controllers\Controller;
use CALwebtool\Submission;

use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    //
    
    public function index(){
        
    }

    public function create(Submission $submissions){
        if(Auth::user()->cannot('judge',$submissions)){
            flash()->overlay("You do not have permission to judge submissions in this group","Not Authorized");
            return redirect()->back();
        }
        return view('scores.create',compact('submissions'));
    }
    
    public function store(Submission $submissions, Request $request){
        $this->validate($request,[
            "numerical_score"=>"required|numeric|min:0|max:10",
            "comment"=>"required|string",
        ]);

        try {

            $form = $submissions->formdefinition()->first();
            $due_date = Carbon::createFromFormat('Y-m-d H:i:s',$form->scores_due);

            if(Auth::user()->cannot('judge',$submissions)){
                flash()->overlay("You do not have permission to judge submissions in this group","Not Authorized");
                return redirect()->back();
            }

            if($due_date < Carbon::now()){
                flash()->overlay("The scores were due on ".$due_date->toDayDateTimeString().".  If you need additional time, please contact your Team administrator to extend the deadline");
                return redirect()->back();
            }

            if($submissions->scores()->where('user_id',Auth::user()->id)->get()->count() > 0){
                flash()->overlay("You have already judged this submission.  You cannot submit a second score, but you may change your initial score.","Sorry");
                return redirect()->back();
            }

            $score = new Scores([
                "form_definition_id" => $form->id,
                'user_id'=>Auth::user()->id,
                "submission_id" => $submissions->id,
                "score" => $request->input("numerical_score"),
                "comment" => $request->input("comment"),
                "status" => "Provisional"
            ]);
            $score->save();

            flash()->overlay("Your score was saved.  You can return anytime before the due date (".
                Carbon::createFromFormat('Y-m-d H:i:s',$form->scores_due)->toDayDateTimeString()
                .")
            to make changes.  After the due date, all scores are final.","Submission Scored!");
            return redirect(action("ScoreController@edit",compact('submissions','score')));
        }
        catch(\Exception $e){
            flash()->overlay("There was a technical problem saving the score.  Restart your Internet browser and try again.  If the problem persists, contact your Team or System administrator","Sorry");
            return redirect()->back();
        }

    }

    public function edit(Submission $submissions, Scores $scores){
        if(Auth::user()->cannot('judge',$submissions)){
            flash()->overlay("You do not have permission to judge submissions in this group","Not Authorized");
            return redirect()->back();
        }
        return view("scores.edit",compact('submissions','scores'));
    }
    
    public function update(){
        
    }
}
