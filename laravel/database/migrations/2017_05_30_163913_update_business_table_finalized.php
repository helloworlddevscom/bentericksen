<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateBusinessTableFinalized extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('business', 'finalized')) {
            Schema::table('business', function (Blueprint $table) {
                $table->boolean('finalized');
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
        if (Schema::hasColumn('business', 'finalized')) {
            Schema::table('business', function (Blueprint $table) {
                $table->dropColumn('finalized');
            });
        }
    }
}
