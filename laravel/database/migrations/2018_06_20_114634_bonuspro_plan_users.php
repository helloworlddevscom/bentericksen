<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BonusproPlanUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonuspro_plan_users', function (Blueprint $table) {
            $table->integer('plan_id')->unsigned();
            $table->integer('user_id')->unsigned();
            // Keys
            $table->foreign('plan_id')->references('id')->on('bonuspro_plans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonuspro_plan_users');
    }
}
