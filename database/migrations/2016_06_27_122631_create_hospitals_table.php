<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->increments('hos_id');
            $table->string('hos_name', 255);
            $table->text('hos_description');
            $table->string('hos_logo', 255);
            $table->text('hos_address');
            $table->text('hos_technical_address');
            $table->text('hos_description_about');
            $table->boolean('hos_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hospitals');
    }
}
