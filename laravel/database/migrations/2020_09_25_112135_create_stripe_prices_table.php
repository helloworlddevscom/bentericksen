<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('stripe_prices')) {
            Schema::create('stripe_prices', function (Blueprint $table) {
                $table->bigIncrements('price_id');
                $table->string('id')->unique();
                $table->bigInteger('prod_id')->unsigned();
                $table->integer('unit_amount');
                $table->string('description');
                $table->foreign('prod_id')
                    ->references('prod_id')->on('stripe_products')
                    ->onDelete('cascade');
                $table->timestamps();
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
        Schema::dropIfExists('stripe_prices');
    }
}
