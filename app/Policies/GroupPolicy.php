<?php

namespace CALwebtool\Policies;
use CALwebtool\User;
use CALwebtool\Group;

use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
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

    public function createform(User $user, Group $group){
        try {
            if ($group->isCreator($user->id) || $group>isAdmin($user->id)) {
                return true;
            } else {
                return false;
            }
        }
        catch(\Exception $e){
            dd($e->getMessage());
            return false;
        }
    }
}
