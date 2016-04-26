<?php

namespace CALwebtool\Policies;

use CALwebtool\User;
use CALwebtool\Group;
use CALwebtool\Submission;

use Illuminate\Auth\Access\HandlesAuthorization;

class SubmissionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before($user, $ability)
    {
        if ($user->isSystemAdmin()) {
            return true;
        }
    }

    public function create(){
        return true;
    }


    public function approve(User $user, Submission $submission)
    {
        $group = $submission->group()->first();
        if($group->isJudge($user->id)){
            return true;
        }
        else{
            return false;
        }
    }

    public function judge(User $user, Submission $submission){
        $group = $submission->group()->first();
        dd($group->isJudge($user->id));
        if($group->isJudge($user->id)){
            return true;
        }
        else if($group->isAdmin($user->id)){
            return true;
        }
        else{
            return false;
        }
    }

    public function reject(User $user, Submission $submission)
    {
        $group = $submission->group()->first();
        if ($group->isJudge($user)) {
            return true;
        }
        elseif($group->isMod($user->id)){
            return true;
        }
        else{
            return false;
        }
    }

    public function delete(User $user, Submission $submission){
        $group = $submission->group()->first();
        if($group->isAdmin($user->id)){
            return true;
        }
        elseif($group->isMod($user->id)){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function allowEditing(User $user, Submission $submission){
        $group = $submission->group()->first();
        
    }


    public function moderate(User $user, Submission $submission){
        $group = $submission->group()->first();
        if($group->isAdmin($user->id)){
            return true;
        }
        elseif($group->isMod($user->id)){
            return true;
        }
        else{
            return false;
        }
    }

    public function sendToJudges(User $user, Submission $submission){
        $group = $submission->group()->first();
        if($group->isAdmin($user->id)){
            return true;
        }
        elseif($group->isMod($user->id)){
            return true;
        }
        else{
            return false;
        }
    }
}
