<?php

namespace CALwebtool\Http\Controllers;

use CALwebtool\FormDefinition;
use Illuminate\Http\Request;

use CALwebtool\Http\Requests;
use CALwebtool\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class SubmissionController extends Controller
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

    public function store(Request $request, FormDefinition $form){
        dd($request->input());

        $this->validate($request,[
            'name' => 'required|max:255|string',
            'email' => 'required|email',
        ]);

        $fields = new Collection();

        foreach($request->input() as $submission_key=>$submission_value){
            if($submission_key == 'name' || $submission_key =='email'){
                continue;
            }
            dd($form->fields());
            $form_field = $form->fields()->where('field_id',$submission_key)->firstOrFail();
            $fields->put($submission_key,$submission_value);
        }
        dd($fields);
    }

}
