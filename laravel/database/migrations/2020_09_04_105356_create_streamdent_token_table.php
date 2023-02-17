<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamdentTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('streamdent_token')) {
            Schema::create('streamdent_token', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->string('access_token');
                $table->dateTime('token_expiration');
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
        Schema::dropIfExists('streamdent_token');
    }
}
