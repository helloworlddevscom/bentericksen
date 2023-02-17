<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateTimeoffRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timeoff_requests', function (Blueprint $table) {
            $table->integer('request_pto_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timeoff_requests', function (Blueprint $table) {
            $table->dropColumn('request_pto_time');
        });
    }
}
