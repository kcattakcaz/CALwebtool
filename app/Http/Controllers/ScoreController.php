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
        $scores = $submissions->scores()->where('user_id',Auth::user()->id)->first();
        if($scores !== null){
            return redirect(action('ScoreController@edit',compact('submissions','scores')));
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
                'user_name'=>Auth::user()->name,
                "comment" => $request->input("comment"),
                "status" => "Provisional"
            ]);
            $score->save();

            flash()->overlay("Your score was saved.  You can return anytime before the due date (".
                Carbon::createFromFormat('Y-m-d H:i:s',$form->scores_due)->toDayDateTimeString()
                .")
            to make changes.  After the due date, all scores are final.","Submission Scored!");
            //return redirect(action("ScoreController@edit",compact('submissions','score')));
            return redirect(action('SubmissionController@show',compact('submissions')));
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


    public function update(Submission $submissions, Scores $scores, Request $request){
        $this->validate($request,[
            "numerical_score" => "required|integer",
            "comment"=>"required|string"
        ]);
        if(Auth::user()->cannot('judge',$submissions)){
            flash()->overlay("You do not have permission to judge submissions in this group","Not Authorized");
            return redirect()->back();
        }
        try{
            $scores->score = $request->input("numerical_score");
            $scores->comment = $request->input("comment");
            $scores->save();
            return redirect(action('SubmissionController@show',compact('submissions')));
        }
        catch(\Exception $e){
            flash()->overlay("The score cannot be updated".$e->getMessage(),"Error");
            return redirect()->back();
        }
    }

    public static function autoSubmissionStatus(){
        echo "<hr><br>Starting automated update of status of submissions in judging queue at ".Carbon::now('America/Detroit')->toDayDateTimeString()."<br>";
        $submissions = Submission::where('status','Judging')->get();
        foreach($submissions as $submission){
            $form = $submission->formdefinition()->first();
            echo "<hr>";
            echo "Submission: ".$submission->id." by ".$submission->name." for ".$form->name."<br>";

            $judges = $form->judges()->get();
            $count = 0;
            foreach($judges as $judge){
                if($submission->scores()->where('user_id',$judge->id)->get()->count() ==0){
                    echo "Judge ".$judge->name." has no score recorded<br>";
                    continue;
                }
                else{
                    echo "Judge ".$judge->name." has a recorded score<br>";
                    $count++;
                }

            }
            if($count == $judges->count()){
                echo "All judges have scored, submission status is now Judged.<br>";
                $submission->status = "Judged";
                $submission->save();
            }

        }
        echo "<br>Ending automated update of status of submissions in judging queue at ".Carbon::now('America/Detroit')->toDayDateTimeString()."<br><hr>";
    }
}
