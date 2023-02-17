<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaperworkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paperwork', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('users_paperwork', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('paperwork_id')->unsigned();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('paperwork_id')
                  ->references('id')
                  ->on('paperwork')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users_paperwork');
        Schema::drop('paperwork');
    }
}
