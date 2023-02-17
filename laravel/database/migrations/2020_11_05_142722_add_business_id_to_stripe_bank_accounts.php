<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBusinessIdToStripeBankAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('stripe_bank_accounts', 'business_id')) {
            Schema::table('stripe_bank_accounts', function (Blueprint $table) {
                $table->integer('business_id')->unsigned()->after('user_id');
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
        if (!Schema::hasColumn('stripe_bank_accounts', 'business_id')) {
            Schema::table('stripe_bank_accounts', function (Blueprint $table) {
                $table->dropColumn('business_id');
            });
        }
    }
}
