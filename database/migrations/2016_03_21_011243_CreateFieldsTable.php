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
            $table->integer('formdefinition_id')->unsigned();
            $table->string('name');
            $table->integer('order');
            $table->enum('type',array('Text','Checkbox','Radios','Select','File'));
            $table->string('options');
            $table->timestamps();

            $table->foreign('formdefinition_id')->references('id')->on('groups');

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
    }
}
