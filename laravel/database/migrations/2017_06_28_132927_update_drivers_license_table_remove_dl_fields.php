<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateDriversLicenseTableRemoveDlFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver_licenses', function (Blueprint $table) {
            $table->dropColumn('number');
            $table->dropColumn('state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('driver_licenses', function (Blueprint $table) {
            $table->string('number')->nullable()->after('user_id');
            $table->string('state')->nullable()->after('number');
        });
    }
}
