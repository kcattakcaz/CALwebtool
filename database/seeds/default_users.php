<?php

use Illuminate\Database\Seeder;

class default_users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \CALwebtool\User();
        $user->name = "System Administrator";
        $user->password = bcrypt("password");
        $user->email = "admin@localhost.localdomain";
        $user->active = true;

        $user->save();
    }
}
