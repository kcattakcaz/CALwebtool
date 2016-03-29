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
        return $this->belongsToMany('CALwebtool\User')->withPivot('creator',
                                                                    'moderator',
                                                                    'adjudicator',
                                                                    'administrator')->withTimestamps();
    }

    public function administratorUsers(){
        return $this->users()->wherePivot('administrator',true);
    }

    public function standardUsers(){
        return $this->users()->wherePivot('administrator',false);
    }

    public function makeAdmin($user){
        try {
            if ($this->users()->find($user->id) !== null){
                $this->users()->sync([$user->id => ['administrator' => true]], false);
                $this->users()->sync([$user->id => ['creator' => true]], false);
                $this->users()->sync([$user->id => ['moderator' => true]], false);
                $this->users()->sync([$user->id => ['adjudicator' => true]], false);
                return true;
            } else {
                $this->users()->save($user, ['administrator' => true,
                                                'creator' => true,
                                                'moderator' => true,
                                                'adjudicator' => true]);
                return true;
            }
        }
        catch(QueryException $e){
            return false;
        }

    }

    public function removeAdmin($user){
        try {
            $this->users()->sync([$user->id => ['administrator' => false]], false);
            $this->users()->sync([$user->id => ['creator' => false]], false);
            $this->users()->sync([$user->id => ['moderator' => false]], false);
            $this->users()->sync([$user->id => ['adjudicator' => false]], false);
            return true;
        }
        catch(QueryException $e){
            return false;
        }
    }

    public function addUser($user, $creator = false, $moderator = false, $adjudicator = false){
        try {
            $this->users()->save($user, ['administrator' => false,
                                            'creator' => $creator,
                                            'moderator' => $moderator,
                                            'adjudicator' => $adjudicator]);
            return true;
        }
        catch(QueryException $e){
            return false;
        }
    }

    public function modifyPermissions($user, $creator = false, $moderator = false, $adjudicator = false){
        try{
            $this->users()->sync([$user->id => ['creator' => $creator]], false);
            $this->users()->sync([$user->id => ['moderator' => $moderator]], false);
            $this->users()->sync([$user->id => ['adjudicator' => $adjudicator]], false);
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
