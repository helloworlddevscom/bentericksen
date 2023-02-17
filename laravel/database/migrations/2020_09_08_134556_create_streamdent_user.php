<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamdentUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('streamdent_user')) {
            Schema::create('streamdent_user', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->integer('user_id');
                $table->integer('streamdent_id');
                $table->string('password', 255);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('streamdent_user');
    }
}
