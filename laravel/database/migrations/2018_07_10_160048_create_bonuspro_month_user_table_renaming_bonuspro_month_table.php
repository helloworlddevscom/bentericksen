<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusproMonthUserTableRenamingBonusproMonthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('bonuspro_plan_month', 'bonuspro_month');
        Schema::create('bonuspro_month_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('month_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->decimal('hours_worked', 10, 2);
            $table->decimal('salary', 10, 2);
            $table->decimal('amount_received', 10, 2);
            $table->timestamps();
            // Keys
            $table->foreign('month_id')->references('id')->on('bonuspro_month')->onDelete('cascade');
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
        Schema::dropIfExists('bonuspro_month_user');
        Schema::rename('bonuspro_month', 'bonuspro_plan_month');
    }
}
