<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldOfCallBackRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('call_back_requests', function ($table) {
            $table->string('name', 255);
            $table->string('phone', 255);
            $table->integer('research')->unsigned()->nullable();
            $table->text('message')->nullable();
            $table->smallInteger('status')->nullable();
            $table->text('comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('call_back_requests', function ($table) {
            $table->dropColumn('name');
            $table->dropColumn('phone');
            $table->dropColumn('research');
            $table->dropColumn('message');
            $table->dropColumn('status');
            $table->dropColumn('comments');
        });
    }
}
