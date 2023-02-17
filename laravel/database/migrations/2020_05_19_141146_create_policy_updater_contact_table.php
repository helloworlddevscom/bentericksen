<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePolicyUpdaterContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policy_updater_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('policy_updater_id');
            $table->string('email');
            $table->enum('contact_type', ['primary', 'additional'])->default('primary');
            $table->timestamps();
            $table->foreign('policy_updater_id')->references('id')->on('policy_updater');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('policy_updater_contacts');
    }
}
