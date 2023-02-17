<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFormTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->nullable();
            $table->string('name')->nullable();
            $table->string('number')->nullable();
            $table->string('category_id')->nullable();
            $table->string('type')->nullable();
            $table->text('description');
            $table->integer('is_default')->nullable();
            $table->string('status')->nullable();
            $table->string('state')->nullable();
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
        Schema::drop('form_templates');
    }
}
