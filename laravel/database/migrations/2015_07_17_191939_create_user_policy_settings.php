<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserPolicySettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_policy_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('business_id');

            /*medical*/
            $table->integer('medical_offered')->nullable();
            $table->integer('medical_waiting_period')->nullable();
            $table->integer('medical_pay_up_to')->nullable();
            $table->integer('medical_cap_amount')->nullable();
            $table->integer('medical_consideration_pay')->nullable();
            $table->integer('medical_consideration_pay_amount')->nullable();

            /*vision*/
            $table->integer('vision_offered')->nullable();
            $table->integer('vision_waiting_period')->nullable();
            $table->string('vision_status')->default('pending');
            $table->integer('vision_pay_up_to')->nullable();
            $table->integer('vision_paperwork_completed')->nullable();

            /*dental*/
            $table->integer('dental_offered')->nullable();
            $table->string('dental_type')->default('default');
            $table->integer('dental_waiting_period')->nullable();
            $table->integer('dental_default_pay_up_to')->nullable();
            $table->integer('dental_default_cap_amount')->nullable();

            $table->integer('dental_discount_percent')->nullable();
            $table->integer('dental_discount_family_benefits')->nullable();
            $table->integer('dental_discount_family_percent')->nullable();

            $table->integer('dental_pdba_credit')->nullable();
            $table->integer('dental_pdba_cap_amount')->nullable();
            $table->integer('dental_pdba_family_benefits')->nullable();
            $table->integer('dental_pdba_family_benefits_type')->nullable();
            $table->integer('dental_pdba_family_benefits__pdba_percent')->nullable();

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
        Schema::drop('user_policy_settings');
    }
}
