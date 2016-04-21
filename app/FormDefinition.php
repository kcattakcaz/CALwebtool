<?php

namespace CALwebtool;

use Illuminate\Database\Eloquent\Model;

class FormDefinition extends Model
    //
{
    protected $table = 'formdefinitions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   /* protected $fillable = [
        'group_id','user_id', 'name','description', 'submissions_start','submissions_end','scores_due'
    ];*/
    protected $fillable= [
            'name','description','group_id','user_id','submissions_start','submissions_end','scores_due','status','created_at','updated_at'
    ];


    public function user(){
        return $this->belongsTo('CALwebtool\User');
    }

    public function group(){
        return $this->belongsTo('CALwebtool\Group');
    }

    public function fields(){
        return $this->hasMany('CALwebtool\Field');
    }

    public function submissions(){
        return $this->hasMany('CALwebtool\Submission','form_definition_id');
    }
}
