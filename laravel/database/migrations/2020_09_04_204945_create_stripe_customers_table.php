<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('stripe_customers')) {
            Schema::create('stripe_customers', function (Blueprint $table) {
                $table->bigIncrements('cus_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->string('address')->nullable();
                $table->integer('balance')->nullable();
                $table->timestamp('created')->nullable();
                $table->string('currency')->nullable();
                $table->string('default_source')->nullable();
                $table->boolean('delinquent')->nullable();
                $table->string('description')->nullable();
                $table->string('discount')->nullable();
                $table->string('email')->nullable();
                $table->string('invoice_prefix')->nullable();
                $table->boolean('livemode')->nullable();
                $table->longText('metadata')->nullable();
                $table->string('name')->nullable();
                $table->integer('next_invoice_sequence')->nullable();
                $table->string('shipping')->nullable();
                $table->string('tax_exempt')->nullable();
                $table->foreign('user_id')
                    ->references('id')->on('users')
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
        Schema::dropIfExists('stripe_customers');
    }
}
