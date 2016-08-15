<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HospitalGeneralOffices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospitals', function ($table) {
            $table->boolean('is_general')->default(false)->comment('Главный офис');
            $table->integer('general_hospital_id')->nullable()->default(null);
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
            $table->dropColumn('is_general');
            $table->dropColumn('general_hospital_id');
        });
    }
}
