<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBonusproMonthUserRenameSalaryColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bonuspro_month_user', function (Blueprint $table) {
            $table->renameColumn('salary', 'gross_pay');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bonuspro_month_user', function (Blueprint $table) {
            $table->renameColumn('gross_pay', 'salary');
        });
    }
}
