<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateTimeoffRequestsTableAddRequestType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timeoff_requests', function (Blueprint $table) {
            $table->text('request_type', 100)->after('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('timeoff_requests', 'request_type')) {
            Schema::table('timeoff_requests', function (Blueprint $table) {
                $table->dropColumn('request_type');
            });
        }
    }
}
