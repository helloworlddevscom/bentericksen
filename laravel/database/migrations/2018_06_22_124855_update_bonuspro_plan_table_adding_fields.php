<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBonusproPlanTableAddingFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bonuspro_plans', function (Blueprint $table) {
            $table->renameColumn('name', 'plan_name');
            $table->dropColumn('start_date');
            $table->string('plan_id', 10)->after('id');
            $table->tinyInteger('start_month')->after('created_by');
            $table->smallInteger('start_year')->after('start_month');
            $table->string('distribution_type', 50)->after('start_year');
            $table->tinyInteger('rolling_average')->after('distribution_type');
            $table->decimal('staff_bonus_percentage', 10, 2)->after('rolling_average');
            $table->string('type_of_practice', 50)->after('staff_bonus_percentage');
            $table->boolean('hygiene_plan')->default(0)->after('staff_bonus_percentage');
            $table->boolean('separate_fund')->default(0)->after('hygiene_plan');
            $table->boolean('use_business_address')->default(1)->after('separate_fund');
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
            $table->renameColumn('plan_name', 'name');
            $table->dropColumn('plan_id');
            $table->dropColumn('start_month');
            $table->dropColumn('start_year');
            $table->dropColumn('rolling_average');
            $table->dropColumn('distribution_type');
            $table->dropColumn('staff_bonus_percentage');
            $table->dropColumn('hygiene_plan');
            $table->dropColumn('separate_fund');
            $table->timestamp('start_date')->nullable();
        });
    }
}
