<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserPolicyUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_policy_updates', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('policy_updater_id');
            $table->timestamp('accepted_at')->default('0000-00-00 00:00:00');
            $table->text('policies')->nullable();
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
        Schema::drop('user_policy_updates');
    }
}
