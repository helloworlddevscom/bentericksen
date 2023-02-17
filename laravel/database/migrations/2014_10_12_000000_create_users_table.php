<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->nullable();
            $table->integer('classification_id')->nullable();
            $table->string('email')->unique();
            $table->string('password', 60);

            $table->rememberToken();
            $table->timestamp('last_login')->nullable();

            //status
            $table->timestamp('hired')->nullable();
            $table->timestamp('rehired')->nullable();
            $table->integer('job_title_id')->nullable();
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->string('status')->nullable()->default('enabled');

            $table->integer('salary')->nullable();
            $table->string('salary_rate')->nullable();
            $table->timestamp('terminated')->nullable();
            $table->timestamp('benefit_date')->nullable();
            $table->integer('years_of_service')->default(0);
            $table->integer('benefit_years_of_service')->default(0);
            $table->string('on_leave')->nullable();

            //contact
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('prefix')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone1_type')->nullable();
            $table->string('phone2')->nullable();
            $table->string('phone2_type')->nullable();

            $table->boolean('included_in_employee_count')->default(1);
            $table->boolean('can_access_system')->default(1);
            $table->boolean('receives_benefits')->default(1);

            $table->boolean('employee_wizard')->default(0);

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
        Schema::drop('users');
    }
}
