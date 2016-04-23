<?php

namespace CALwebtool\Policies;

use CALwebtool\FormDefinition;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormPolicy
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
    

    public function update(User $user, FormDefinition $form){
        try {
            if ($form->group()->first()->isCreator($user->id) || $form->group()->first()->isAdmin($user->id)) {
                return true;
            } else {
                return false;
            }
        }
        catch(\Exception $e){
            return false;
        }
    }

    public function retrieve(User $user, FormDefinition $form){
        try {
            if ($form->group()->first()->users()->get()->contains($user)) {
                return true;
            } else {
                return false;
            }
        }
        catch(\Exception $e){
            dd($e);
        }
    }

    public function delete(User $user, FormDefinition $form){
        try {
            if ($form->group()->first()->isCreator($user->id) || $form->group()->first()->isAdmin($user->id)) {
                return true;
            } else {
                return false;
            }
        }
        catch(\Exception $e){
            return false;
        }
    }

    public function schedule(User $user, FormDefinition $form){
        try {
            if ($form->group()->first()->isCreator($user->id) || $form->group()->first()->isAdmin($user->id)) {
                return true;
            } else {
                return false;
            }
        }
        catch(\Exception $e){
            return false;
        }
    }
    
}
