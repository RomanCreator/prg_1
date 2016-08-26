<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SideContentView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('researches', function ($table) {
            //$table->string('therapeutic_areas', 255)->nullable()->comment('Лечебные направления');
            $table->boolean('show_state')->default(false)->comment('Отображение в левой стороне на внутренних страницах');
            $table->integer('show_position')->default(0)->comment('Позиция в списке на внутренних страницах');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('researches', function ($table) {
            $table->dropColumn('show_state');
            $table->dropColumn('show_position');
        });
    }
}
