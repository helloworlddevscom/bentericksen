<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoginToStreamdentUsers extends Migration
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
                $table->string('login');
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
            $table->dropColumn('login');
        });
    }
}
