<?php

use App\History;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteLastLoginHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        History::where('type', 'Status')
            ->where('note', 'like', 'last_login updated from %')
            ->where('status', 'active')
            ->where('created_at', '>', '2018-12-19 00:00:00')
            ->delete();
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
