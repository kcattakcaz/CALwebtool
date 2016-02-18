<?php

namespace CALwebtool;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];



    public function users(){
        return $this->belongsToMany('CALwebtool\User')->withPivot('creator','moderator','adjudicator','administrator')->withTimestamps();
    }

    public function administratorUsers(){
        return $this->users()->wherePivot('administrator',true);
    }

    public function standardUsers(){
        return $this->users()->wherePivot('administrator',false);
    }

    public function isAdmin($user_id){
        try {
            return $this->users()->findOrFail($user_id)->pivot->administrator;
        }
        catch(\Exception $e){
            return false;
        }
    }
}
