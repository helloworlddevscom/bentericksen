<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone1_type')->nullable();
            $table->string('phone2')->nullable();
            $table->string('phone2_type')->nullable();
            $table->string('phone3')->nullable();
            $table->string('phone3_type')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->integer('primary_user_id');
            $table->string('primary_role');

            $table->string('secondary_1_first_name')->nullable();
            $table->string('secondary_1_middle_name')->nullable();
            $table->string('secondary_1_last_name')->nullable();
            $table->string('secondary_1_prefix')->nullable();
            $table->string('secondary_1_suffix')->nullable();
            $table->string('secondary_1_email')->nullable();
            $table->string('secondary_1_role')->nullable();
            $table->string('secondary_2_first_name')->nullable();
            $table->string('secondary_2_middle_name')->nullable();
            $table->string('secondary_2_last_name')->nullable();
            $table->string('secondary_2_prefix')->nullable();
            $table->string('secondary_2_suffix')->nullable();
            $table->string('secondary_2_email')->nullable();
            $table->string('secondary_2_role')->nullable();

            $table->string('type')->nullable();
            $table->string('subtype')->nullable();
            $table->integer('consultant_user_id')->nullable();
            $table->string('status')->nullable();
            $table->integer('asa_id')->nullable();
            $table->integer('do_not_contact')->nullable();
            $table->string('referral')->nullable();
            $table->integer('additional_employees')->default(0);
            $table->string('manual', 45)->nullable();

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
        Schema::drop('business');
    }
}
