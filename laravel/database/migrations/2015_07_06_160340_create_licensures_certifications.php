<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLicensuresCertifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licensure_certifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id');
            $table->string('name');
            $table->string('status');
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('user_licensure_certifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('licensure_certifications_id');
            $table->timestamp('expiration')->nullable();
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
        Schema::drop('licensure_certifications');
        Schema::drop('user_licensure_certifications');
    }
}
