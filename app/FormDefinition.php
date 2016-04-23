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
        'name', 'description', 'group_id', 'user_id', 'submissions_start', 'submissions_end', 'scores_due', 'status', 'created_at', 'updated_at', 'notify_completed_sent', 'sub_accept_action', 'sub_accept_content', 'use_custom_css', 'custom_css_url'
    ];


    public function user()
    {
        return $this->belongsTo('CALwebtool\User');
    }

    public function group()
    {
        return $this->belongsTo('CALwebtool\Group');
    }

    public function fields()
    {
        return $this->hasMany('CALwebtool\Field');
    }

    public function submissions()
    {
        return $this->hasMany('CALwebtool\Submission', 'form_definition_id');
    }

    public function judges()
    {
        return $this->belongsToMany('CALwebtool\Users','form_users','form_id','user_id');
    }
}
