<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFaqTableTextColumnTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Couldn't use the ->change() method because there of a bug. For more info see:
        // https://github.com/laravel/framework/issues/12363
        DB::statement('ALTER TABLE faqs CHANGE short_answer short_answer LONGTEXT;');
        DB::statement('ALTER TABLE faqs CHANGE long_answer long_answer LONGTEXT;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
