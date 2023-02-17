<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBonusproPlanTableAddProgressRelatedColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bonuspro_plans', function (Blueprint $table) {
            $table->string('status', 50)->after('type_of_practice');
            $table->boolean('completed')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bonuspro_plans', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('completed');
        });
    }
}
