<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TimeOffRemainingNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timeoff_requests', function (Blueprint $table) {
            $table->string('remaining')->nullable()->change();
            $table->integer('request_pto_time')->default(0)->change();
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
            $table->string('remaining')->change();
            $table->integer('request_pto_time')->change();
        });
    }
}
