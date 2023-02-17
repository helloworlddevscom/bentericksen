<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmergencyContact extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergency_contact', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone1_type')->nullable();
            $table->string('phone2')->nullable();
            $table->string('phone2_type')->nullable();
            $table->string('phone3')->nullable();
            $table->string('phone3_type')->nullable();
            $table->string('relationship')->nullable();
            $table->boolean('is_primary')->default(0);
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
        Schema::drop('emergency_contact');
    }
}
