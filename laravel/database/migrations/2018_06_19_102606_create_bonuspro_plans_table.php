<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusproPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonuspro_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('password', 100);
            $table->timestamp('start_date');
            $table->integer('business_id')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            // Keys
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonuspro_plans');
    }
}
