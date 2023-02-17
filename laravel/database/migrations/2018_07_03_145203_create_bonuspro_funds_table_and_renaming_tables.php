<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusproFundsTableAndRenamingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('bonuspro_plans', 'bonuspro_plan');
        Schema::rename('bonuspro_plans_addresses', 'bonuspro_plan_address');
        Schema::rename('bonuspro_plan_users', 'bonuspro_plan_user');

        Schema::create('bonuspro_fund', function ($table) {
            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->string('fund_id', 10);
            $table->string('fund_name', 10);
            $table->tinyInteger('fund_start_month');
            $table->smallInteger('fund_start_year');
            $table->string('fund_type', 50);
            $table->decimal('fund_amount', 10, 2);
            $table->timestamps();
            $table->softDeletes();
            // Keys
            $table->foreign('plan_id')->references('id')->on('bonuspro_plan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('bonuspro_plan', 'bonuspro_plans');
        Schema::rename('bonuspro_plan_address', 'bonuspro_plans_addresses');
        Schema::rename('bonuspro_plan_user', 'bonuspro_plan_users');
        Schema::dropIfExists('bonuspro_fund');
    }
}
