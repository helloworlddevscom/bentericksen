<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('stripe_cards')) {
            Schema::create('stripe_cards', function (Blueprint $table) {
                $table->bigIncrements('card_id');
                $table->string('id')->unique();
                $table->integer('user_id')->unsigned();
                $table->string('address_city')->nullable();
                $table->string('address_country')->nullable();
                $table->string('address_line1')->nullable();
                $table->string('address_line1_check')->nullable();
                $table->string('address_line2')->nullable();
                $table->string('address_state')->nullable();
                $table->string('address_zip')->nullable();
                $table->string('address_zip_check')->nullable();
                $table->string('brand')->nullable();
                $table->string('country')->nullable();
                $table->string('customer')->nullable();
                $table->string('cvc_check')->nullable();
                $table->string('dynamic_last4')->nullable();
                $table->integer('exp_month')->nullable();
                $table->integer('exp_year')->nullable();
                $table->string('fingerprint')->nullable();
                $table->string('funding')->nullable();
                $table->string('last4')->nullable();
                $table->longText('metadata')->nullable();
                $table->string('name')->nullable();
                $table->string('tokenization_method')->nullable();
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
        Schema::dropIfExists('stripe_cards');
    }
}
