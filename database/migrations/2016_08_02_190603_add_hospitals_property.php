<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHospitalsProperty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospitals', function ($table) {
            $table->string('district', 255)->nullable()->after('address')->comment('Район города');
            $table->string('subway', 255)->nullable()->after('district')->comment('Ближайшее метро');
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
            $table->dropColumn('district');
            $table->dropColumn('subway');
        });
    }
}
