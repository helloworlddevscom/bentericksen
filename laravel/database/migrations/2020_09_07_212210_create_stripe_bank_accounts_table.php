<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('stripe_bank_accounts')) {
            Schema::create('stripe_bank_accounts', function (Blueprint $table) {
                $table->bigIncrements('account_id');
                $table->string('id')->unique();
                $table->integer('user_id')->unsigned();
                $table->string('account_holder_name')->nullable();
                $table->string('account_holder_type')->nullable();
                $table->string('bank_name')->nullable();
                $table->string('currency')->nullable();
                $table->string('country')->nullable();
                $table->string('customer')->nullable();
                $table->string('fingerprint')->nullable();
                $table->string('last4')->nullable();
                $table->longText('metadata')->nullable();
                $table->string('routing_number')->nullable();
                $table->string('status')->nullable();
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
        Schema::dropIfExists('stripe_bank_accounts');
    }
}
