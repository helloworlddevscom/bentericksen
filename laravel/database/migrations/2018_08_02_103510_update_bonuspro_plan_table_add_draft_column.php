<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBonusproPlanTableAddDraftColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bonuspro_plan', function (Blueprint $table) {
            $table->boolean('draft')->default(0)->after('status');
            $table->string('plan_id', 10)->nullable()->default(null)->change();
            $table->string('plan_name', 100)->nullable()->default(null)->change();
            $table->string('password', 100)->nullable()->default(null)->change();
            $table->unsignedSmallInteger('start_month')->nullable()->default(null)->change();
            $table->unsignedSmallInteger('start_year')->nullable()->default(null)->change();
            $table->string('distribution_type', 50)->nullable()->default(null)->change();
            $table->unsignedSmallInteger('rolling_average')->nullable()->default(null)->change();
            $table->decimal('staff_bonus_percentage', 10, 2)->nullable()->default(null)->change();
            $table->decimal('hygiene_bonus_percentage', 10, 2)->nullable()->default(null)->change();
            $table->string('type_of_practice', 50)->nullable()->default(null)->change();
            $table->string('status', 50)->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bonuspro_plan', function (Blueprint $table) {
            $table->dropColumn('draft');
            $table->string('plan_id', 10)->nullable(false)->change();
            $table->string('plan_name', 100)->nullable(false)->change();
            $table->string('password', 100)->nullable(false)->change();
            $table->unsignedSmallInteger('start_month')->nullable(false)->change();
            $table->unsignedSmallInteger('start_year')->nullable(false)->change();
            $table->string('distribution_type', 50)->nullable(false)->change();
            $table->unsignedSmallInteger('rolling_average')->nullable(false)->change();
            $table->decimal('staff_bonus_percentage', 10, 2)->nullable(false)->change();
            $table->decimal('hygiene_bonus_percentage', 10, 2)->nullable(false)->change();
            $table->string('type_of_practice', 50)->nullable(false)->change();
            $table->string('status', 50)->nullable(false)->change();
        });
    }
}
