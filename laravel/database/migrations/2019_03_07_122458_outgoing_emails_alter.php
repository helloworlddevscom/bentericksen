<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OutgoingEmailsAlter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outgoing_emails', function (Blueprint $table) {
            $table->string('type', 50)->nullable()->after('user_id');
        });
        Schema::table('outgoing_emails', function (Blueprint $table) {
            $table->dropColumn('view_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outgoing_emails', function (Blueprint $table) {
            $table->string('view_name', 255)->nullable()->after('user_id');
        });
        Schema::table('outgoing_emails', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
