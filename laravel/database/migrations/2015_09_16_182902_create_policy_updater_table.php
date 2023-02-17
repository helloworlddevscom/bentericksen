<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePolicyUpdaterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policy_updater', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inactive_clients')->nullable();
            $table->text('policies')->nullable();
            $table->text('faqs')->nullable();
            $table->text('job_descriptions')->nullable();
            $table->string('title')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->text('active_clients_text')->nullable();
            $table->text('inactive_clients_text')->nullable();
            $table->text('additional_text')->nullable();
            $table->text('additional_emails')->nullable();
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
        Schema::drop('policy_updater');
    }
}
