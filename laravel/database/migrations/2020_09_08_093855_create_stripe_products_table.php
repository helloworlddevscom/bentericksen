<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeProductsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('stripe_products')) {
            Schema::create('stripe_products', function (Blueprint $table) {
                $table->bigIncrements('prod_id');
                $table->string('id')->unique();
                $table->integer('active')->default(0);
                $table->timestamp('created')->nullable();
                $table->longText('description')->nullable();
                $table->boolean('livemode')->nullable();
                $table->longText('metadata')->nullable();
                $table->string('name')->nullable();
                $table->string('statement_descriptor')->nullable();
                $table->string('type')->nullable()->default('service');
                $table->string('unit_label')->nullable();
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
        Schema::dropIfExists('stripe_products');
    }
}
