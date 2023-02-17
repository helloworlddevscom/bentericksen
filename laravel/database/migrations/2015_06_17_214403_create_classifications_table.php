<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClassificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id');
            $table->string('name');
            $table->boolean('is_base')->default(0);
            $table->boolean('is_enabled')->default(1);
            $table->integer('minimum_hours')->default(0);
            $table->string('minimum_hours_interval');
            $table->integer('maximum_hours')->default(0);
            $table->string('maximum_hours_interval');
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
        Schema::drop('classifications');
    }
}
