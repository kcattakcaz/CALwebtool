<?php

namespace CALwebtool\Policies;

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

    public function create_form(User $user, Group $group){
        try {
            if ($group->isCreator($user->id) || $group>isAdmin($user->id)) {
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
