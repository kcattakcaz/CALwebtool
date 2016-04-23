<?php

namespace CALwebtool;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{


    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','email', 'password', 'active', 'notify_unmoderated', 'notify_unscored', 'notify_completed'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['created_at','updated_at','deleted_at'];


    public function groups(){
        return $this->belongsToMany('CALwebtool\Group');
    }

    public function forms(){
        return $this->hasMany('CALwebtool\FormDefinition');
    }

    public function creatorGroups(){
        return $this->groups()->wherePivot('creator',true);
    }

    public function moderatorGroups(){
        return $this->groups()->wherePivot('moderator',true);
    }

    public function adminGroups(){
        return $this->groups()->wherePivot('administrator',true);
    }

    public function isSystemAdmin(){
        return $this->system_admin;
    }

    public function judgingForms(){
        return $this->belongsToMany('CALwebtool\FormDefinition','form_users','user_id','form_id')->withTimestamps();
    }
}
