<?php

namespace CALwebtool;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function groups(){
        return $this->belongsToMany('CALwebtool\Group');
    }

    public function creatorGroups(){
        return $this->groups()->wherePivot('creator',true);
    }

    public function isSystemAdmin(){
        return $this->system_admin;
    }

    public function formDefinitions(){
        return $this->hasMany('CALwebtool\FormDefinition');
    }
}
