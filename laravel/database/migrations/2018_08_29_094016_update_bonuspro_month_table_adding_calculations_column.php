<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBonusproMonthTableAddingCalculationsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bonuspro_month', function (Blueprint $table) {
            $table->decimal('production_collection_average', 10, 2)->after('collection_amount');
            $table->decimal('staff_percentage', 10, 2)->nullable()->after('production_collection_average');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bonuspro_month', function (Blueprint $table) {
            $table->dropColumn('production_collection_average');
            $table->dropColumn('staff_percentage');
        });
    }
}
