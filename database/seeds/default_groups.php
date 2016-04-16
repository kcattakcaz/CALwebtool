<?php

use Illuminate\Database\Seeder;
use CALwebtool\User;

class default_groups extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group = new \CALwebtool\Group();
        $group->name = "College of Arts and Letters";
        $group->description ="Default Team for the CAL Alumni Awards Board";
        $group->save();

        $group->makeAdmin(User::find(1));
    }
}
