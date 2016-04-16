<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('form_definition_id')->unsigned();
            $table->string('name');
            $table->string('email');
            $table->string('password')->nullable();
            $table->dateTime('submitted');
            $table->enum('status',array('Reviewing','Judging','Judged','Approved','Denied','Reopened','Special'));
            $table->mediumText('options');
            $table->timestamps();

            $table->foreign('form_definition_id')->references('id')->on('formdefinitions')->onDelete('cascade');

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
        Schema::drop('submissions');
    }
}
