<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJobDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->default(0);
            $table->string('name');
            $table->string('industry')->nullable();
            $table->string('subtype')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_default')->default(0);
            $table->string('status')->default('active');
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
        Schema::drop('job_descriptions');
    }
}
