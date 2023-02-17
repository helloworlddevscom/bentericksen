<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersBusinessAsasBusinessIdIndices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_asas', function (Blueprint $table) {
            $table->index('business_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('business_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_asas', function (Blueprint $table) {
            $table->dropIndex('business_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('business_id');
        });
    }
}
