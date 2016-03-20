<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormDefinitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('formdefinitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->integer('group_id')->unsigned()->onDelete('cascade');
            $table->integer('user_id')->unsigned()->onDelete('set null');
            $table->dateTime('submissions_start');
            $table->dateTime('submissions_end');
            $table->dateTime('scores_due');
            $table->json('fields');
            //$table->enum('status',array('Drafting','Scheduled','Accepting','Reviewing','Scored','Archived'));
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::drop('formdefinitions');
    }
}
