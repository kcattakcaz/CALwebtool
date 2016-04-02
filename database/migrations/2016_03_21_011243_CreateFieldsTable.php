<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string("field_id");
            $table->integer('form_definition_id')->unsigned();
            $table->string('name');
            $table->integer('order');
            $table->enum('type',array('Text','Checkbox','RadioGroup','Select','File'));
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
        Schema::drop('fields');
    }
}
