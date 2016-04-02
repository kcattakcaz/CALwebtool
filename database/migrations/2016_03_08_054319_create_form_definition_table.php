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
            $table->integer('group_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->dateTime('submissions_start');
            $table->dateTime('submissions_end');
            $table->dateTime('scores_due');
            $table->enum('status',array('Drafting','Scheduled','Accepting','Reviewing','Scored','Archived'));
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

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
