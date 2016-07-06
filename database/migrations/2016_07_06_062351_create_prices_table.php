<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hospital_id')->unsigned();
            $table->integer('research_id')->unsigned();
            $table->decimal('price_from', 10, 2);
            $table->decimal('price_to', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(0);


            $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('research_id')->references('id')->on('researches')->onDelete('cascade')->onUpdate('restrict');



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
        Schema::drop('prices');
    }
}
