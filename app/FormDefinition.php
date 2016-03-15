<?php

namespace CALwebtool;

use Illuminate\Database\Eloquent\Model;

class FormDefinition extends Model
    //
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id','user_id', 'submissions_start','submissions_end','scores_due','fields','status'
    ];

    public function user(){
        return $this->belongsTo('CALWebtool\User');
    }

    public function group(){
        return $this->belongsTo('CALWebtool\Group');
    }
}
