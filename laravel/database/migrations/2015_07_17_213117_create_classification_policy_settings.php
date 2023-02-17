<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClassificationPolicySettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classifications_policy_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('classification_id');
            $table->integer('business_id');

            $table->text('medical')->nullable();
            $table->text('vision')->nullable();
            $table->text('dental')->nullable();
            $table->text('vacation_pto')->nullable();
            $table->text('sickleave')->nullable();
            $table->text('holidays')->nullable();
            $table->text('bereavement_leave')->nullable();
            $table->text('petcare')->nullable();
            $table->text('severance')->nullable();
            $table->text('referral')->nullable();
            $table->text('reference')->nullable();
            $table->text('harassment')->nullable();
            $table->text('resolution')->nullable();

            /*
            $table->integer('medical_same_as_base')->default(0);
            $table->integer('medical_does_not_receive')->default(0);
            $table->integer('medical_pay_up_to')->nullable();
            $table->integer('medical_cap_amount')->nullable();
            $table->integer('medical_consideration_pay')->nullable();
            $table->integer('medical_consideration_pay_amount')->nullable();

            $table->integer('vision_same_as_base')->default(0);
            $table->integer('vision_does_not_receive')->default(0);
            $table->string('vision_status')->default('pending');
            $table->integer('vision_pay_up_to')->nullable();
            $table->integer('vision_paperwork_completed')->nullable();

            $table->integer('dental_same_as_base')->default(0);
            $table->integer('dental_does_not_receive')->default(0);
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
            */

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
        Schema::drop('classifications_policy_settings');
    }
}
