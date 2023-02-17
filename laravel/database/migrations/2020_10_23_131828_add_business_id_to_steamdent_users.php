<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBusinessIdToSteamdentUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('streamdent_user')) {
            Schema::table('streamdent_user', function (Blueprint $table) {
                $table->integer('user_id')->nullable()->change();
                $table->integer('business_id')->nullable();

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
        Schema::table('streamdent_user', function (Blueprint $table) {
            $table->integer('user_id')->nullable(false)->change();
            $table->dropColumn('business_id');
        });
    }
}
