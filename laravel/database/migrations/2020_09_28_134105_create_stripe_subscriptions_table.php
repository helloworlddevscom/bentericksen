<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('stripe_subscriptions')) {
            Schema::create('stripe_subscriptions', function (Blueprint $table) {
                $table->bigIncrements('sub_id')->unsigned();
                $table->string('id')->unique();
                $table->string('status')->nullable();
                $table->longText('data')->nullable();
                $table->string('invoice_status')->nullable();
                $table->string('latest_invoice')->nullable();
                $table->integer('business_id')->unsigned()->unique();
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
        Schema::dropIfExists('stripe_subscriptions');
    }
}
