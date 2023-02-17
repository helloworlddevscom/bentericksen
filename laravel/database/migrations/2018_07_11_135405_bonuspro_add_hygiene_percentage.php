<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BonusproAddHygienePercentage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('bonuspro_plan', 'hygiene_bonus_percentage')) {
            Schema::table('bonuspro_plan', function (Blueprint $table) {
                $table->decimal('hygiene_bonus_percentage', 10, 2)
                    ->after('staff_bonus_percentage');
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
        if (Schema::hasColumn('bonuspro_plan', 'hygiene_bonus_percentage')) {
            Schema::table('bonuspro_plan', function (Blueprint $table) {
                $table->dropColumn('hygiene_bonus_percentage');
            });
        }
    }
}
