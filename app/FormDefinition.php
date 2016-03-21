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
    protected $fillable = [
        'group_id','user_id', 'submissions_start','submissions_end','scores_due'
    ];

    public function user(){
        return $this->belongsTo('CALWebtool\User');
    }

    public function group(){
        return $this->belongsTo('CALWebtool\Group');
    }

    public function fields(){
        return $this->hasMany('CALWebtool\Fields');
    }
}
