<?php

namespace CALwebtool;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','form_definition_id','name','email','password','submitted','status','options','judgement'
    ];

    public function formdefinition(){
        return $this->belongsTo('CALwebtool\FormDefinition','form_definition_id');
    }

    public function group(){
        return $this->formdefinition()->first()->group()->first();
    }

    public function scores(){
        return $this->hasMany('CALwebtool\Scores',"submission_id");
    }
}
