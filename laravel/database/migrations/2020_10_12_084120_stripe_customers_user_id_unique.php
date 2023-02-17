<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StripeCustomersUserIdUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('stripe_customers', 'user_id')) {
            Schema::table('stripe_customers', function (Blueprint $table) {
                $table->unique('user_id');
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
        Schema::table('stripe_customers', function (Blueprint $table) {
            $table->dropUnique('user_id');
        });
    }
}
