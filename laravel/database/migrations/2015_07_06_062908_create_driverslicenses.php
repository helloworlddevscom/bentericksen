<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDriverslicenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_licenses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('number')->nullable();
            $table->string('state')->nullable();
            $table->timestamp('expiration')->nullable();
            $table->string('agent')->nullable();
            $table->string('agent_phone')->nullable();
            $table->string('policy_number')->nullable();
            $table->timestamp('policy_expiration')->nullable();
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
        Schema::drop('driver_licenses');
    }
}
