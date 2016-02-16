<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GroupsUsersPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('group_user', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('group_id');
            $table->boolean('creator'); //Can create FormDefinitions, ElectionDefinitions
            $table->boolean('moderator'); //Can create/delete/reject ApplicationSubmissions, BallotSubmissions
            $table->boolean('adjudicator'); //Can create SubmissionScores
            $table->boolean('administrator'); //Can create/update/delete all types and view/restore softDeletes

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('groups_users');
    }
}
