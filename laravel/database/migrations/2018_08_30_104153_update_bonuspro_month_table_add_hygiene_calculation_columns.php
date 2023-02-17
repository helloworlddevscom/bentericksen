<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBonusproMonthTableAddHygieneCalculationColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bonuspro_month', function (Blueprint $table) {
            $table->decimal('hygiene_percentage', 10, 2)->nullable()->after('hygiene_production_amount');
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
            $table->dropColumn('hygiene_percentage');
        });
    }
}
