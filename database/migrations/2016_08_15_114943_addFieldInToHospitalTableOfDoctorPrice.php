<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldInToHospitalTableOfDoctorPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospitals', function ($table) {
            $table->string('doctor_price', 255)->nullable()->comment('Прием врача от');
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
            $table->dropColumn('doctor_price');
        });
    }
}
