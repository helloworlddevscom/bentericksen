<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserJobDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_job_description', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('job_description_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('job_description_id')->references('id')->on('job_descriptions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'job_description_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_job_description');
    }
}
