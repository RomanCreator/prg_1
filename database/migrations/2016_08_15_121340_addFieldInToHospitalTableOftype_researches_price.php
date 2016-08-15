<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldInToHospitalTableOftypeResearchesPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospitals', function ($table) {
            $table->string('type_researches_price', 255)->nullable()->comment('Базовые цены на типы исследований');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospitals', function ($table) {
            $table->dropColumn('type_researches_price');
        });
    }
}
