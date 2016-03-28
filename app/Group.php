<?php

namespace CALwebtool;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

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

    public function makeAdmin($user){
        try {
            if ($this->users()->findOrFail($user->id)) {
                $this->users()->find($user->id)->pivot->administrator = true;
                $this->users()->find($user->id)->pivot->creator = true;
                $this->users()->find($user->id)->pivot->moderator = true;
                $this->users()->find($user->id)->pivot->adjudicator = true;
                return true;
            } else {
                $this->users()->save($user, ['administrator' => true, 'creator' => true, 'moderator' => true, 'adjudicator' => true]);
                return true;
            }
        }
        catch(QueryException $e){
            return false;
        }

    }

    public function removeAdmin($user){
        try {
            $this->users()->findOrFail($user->id)->pivot->administrator = false;
            $this->users()->findOrFail($user->id)->pivot->creator = false;
            $this->users()->findOrFail($user->id)->pivot->moderator = false;
            $this->users()->findOrFail($user->id)->pivot->adjudicator = false;
            return true;
        }
        catch(QueryException $e){
            return false;
        }
    }

    public function addUser($user,$creator = false, $moderator = false, $adjudicator = false){
        try {
            $this->users()->save($user, ['administrator' => false, 'creator' => $creator, 'moderator' => $moderator, 'adjudicator' => $adjudicator]);
            return true;
        }
        catch(QueryException $e){
            return false;
        }
    }

    public function modifyPermissions($user, $creator = false, $moderator = false, $adjudicator = false){
        try{
            $this->users()->findOrFail($user->id)->pivot->creator = $creator;
            $this->users()->findOrFail($user->id)->pivot->moderator = $moderator;
            $this->users()->findOrFail($user->id)->pivot->adjudicator = $adjudicator;
            return true;
        }
        catch(QueryException $e){
            return false;
        }
    }

    public function isAdmin($user_id){
        try {
            return $this->users()->findOrFail($user_id)->pivot->administrator;
        }
        catch(\Exception $e){
            return false;
        }
    }

    public function isMod($user_id){
        try {
            return $this->users()->findOrFail($user_id)->pivot->moderator;
        }
        catch(\Exception $e){
            return false;
        }
    }

    public function isCreator($user_id){
        try {
            return $this->users()->findOrFail($user_id)->pivot->creator;
        }
        catch(\Exception $e){
            return false;
        }
    }

    public function isJudge($user_id){
        try {
            return $this->users()->findOrFail($user_id)->pivot->adjudicator;
        }
        catch(\Exception $e){
            return false;
        }
    }

    public function formDefinitions(){
        return $this->hasMany('CALwebtool\FormDefinition');
    }
}
