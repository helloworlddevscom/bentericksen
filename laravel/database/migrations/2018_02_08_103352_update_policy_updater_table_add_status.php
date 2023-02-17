<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdatePolicyUpdaterTableAddStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('policy_updater', function (Blueprint $table) {
            $table->text('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('policy_updater', 'status')) {
            Schema::table('policy_updater', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
}
