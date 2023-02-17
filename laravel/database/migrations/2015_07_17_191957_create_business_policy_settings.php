<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBusinessPolicySettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_policy_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id');

            /*medical*/
            $table->integer('medical_offered')->default(0);
            $table->integer('medical_waiting_period')->default(0);
            //$table->integer('medical_policy_id');

            /*vision*/
            $table->integer('vision_offered')->default(0);
            $table->integer('vision_waiting_period')->default(0);
            //$table->integer('vision_policy_id');

            /*dental*/
            $table->integer('dental_offered')->default(0);
            $table->string('dental_type')->default('default');
            $table->integer('dental_waiting_period')->default(0);
            //$table->integer('dental_policy_id');

            $table->integer('holiday_offered')->default(0);
            $table->text('holiday_list')->nullable();

            $table->integer('bereavement_leave_offered')->default(0);
            $table->integer('sickleave_offered')->default(0);
            $table->integer('vacation_pto_offered')->default(0);
            $table->integer('severance_offered')->default(0);
            $table->integer('referral_offered')->default(0);
            $table->integer('reference_offered')->default(0);
            $table->integer('harassment_offered')->default(0);
            $table->integer('resolution_offered')->default(0);
            $table->integer('petcare_offered')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('business_policy_settings');
    }
}
