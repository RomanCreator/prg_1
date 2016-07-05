<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaticPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path', 255);
            $table->string('title', 255)->nullable();
            $table->string('keywords', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->text('content')->nullable();
            $table->string('entity', 255)->nullable();
            $table->integer('id_entity')->unsigned()->nullable();
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
        Schema::drop('static_pages');
    }
}
