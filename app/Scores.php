<?php

namespace CALwebtool;

use Illuminate\Database\Eloquent\Model;

class Scores extends Model
{
    //
    protected $fillable = [
        'user_id','form_definition_id','submission_id','user_name','score','comment','status'
    ];

    public function formdefinition(){
        return $this->belongsTo('CALwebtool\FormDefinition','form_definition_id');
    }

    public function submission(){
        return $this->belongsTo('CALwebtool\Submission','submission_id');
    }
}
