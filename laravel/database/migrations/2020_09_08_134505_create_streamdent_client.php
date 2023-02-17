<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamdentClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('streamdent_client')) {
            Schema::create('streamdent_client', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->integer('business_id');
                $table->integer('streamdent_id');
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
        Schema::dropIfExists('streamdent_client');
    }
}
