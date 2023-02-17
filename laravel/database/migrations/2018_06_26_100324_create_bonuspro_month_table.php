<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusproMonthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonuspro_plan_month', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->decimal('production_amount', 10, 2);
            $table->decimal('collection_amount', 10, 2);
            $table->decimal('hygiene_production_amount', 10, 2);
            $table->boolean('finalized')->default(0);
            // keys
            $table->foreign('plan_id')->references('id')->on('bonuspro_plans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonuspro_plan_month');
    }
}
