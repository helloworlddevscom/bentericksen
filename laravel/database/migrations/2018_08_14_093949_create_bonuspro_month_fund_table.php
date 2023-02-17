<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusproMonthFundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonuspro_month_fund', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('month_id')->unsigned();
            $table->integer('fund_id')->unsigned();
            $table->decimal('amount_received', 10, 2);
            $table->timestamps();
            // Keys
            $table->foreign('month_id')->references('id')->on('bonuspro_month')->onDelete('cascade');
            $table->foreign('fund_id')->references('id')->on('bonuspro_fund')->onDelete('cascade');
        });

        /*
         * Removing softDelete on bonuspro_plan
         */
        if (Schema::hasColumn('bonuspro_plan', 'deleted_at')) {
            Schema::table('bonuspro_plan', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        /*
         * Removing softDelete on bonuspro_fund
         */
        if (Schema::hasColumn('bonuspro_fund', 'deleted_at')) {
            Schema::table('bonuspro_fund', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonuspro_month_fund');
    }
}
