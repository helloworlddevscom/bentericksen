<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClassificationUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classification_updates', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('classification_id');
            $table->timestamp('effective_at')->nullable();
            $table->integer('updated');
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
        Schema::drop('classification_updates');
    }
}
