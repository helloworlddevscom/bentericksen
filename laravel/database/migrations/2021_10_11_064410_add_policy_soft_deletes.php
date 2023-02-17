<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPolicySoftDeletes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('policies', 'deleted_at')) {
            return;
        }

        Schema::table('policies', function (Blueprint $table) {
            $table->string('delete_reason', 255)->nullable();
            $table->softDeletes();
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->dropColumn('delete_reason')->nullable();
            $table->dropSoftDeletes();
        });
    }
}
