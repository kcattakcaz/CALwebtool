<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('scores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('form_definition_id')->unsigned();
            $table->integer('submission_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('user_name');
            $table->tinyInteger('score');
            $table->mediumText('comment');
            $table->enum('status',array('Provisional','Final'));
            $table->timestamps();

            $table->foreign('form_definition_id')->references('id')->on('formdefinitions')->onDelete('cascade');
            $table->foreign('submission_id')->references('id')->on('submissions')->onDelete('cascade');
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
        Schema::drop('scores');
    }
}
