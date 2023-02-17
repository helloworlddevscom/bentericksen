<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateBusinessTableManualCreatedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('business', 'manual_created_at')) {
            Schema::table('business', function (Blueprint $table) {
              $table->timestamp('manual_created_at')->default('0000-00-00 00:00:00');
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
        if (Schema::hasColumn('business', 'manual_created_at')) {
            Schema::table('business', function (Blueprint $table) {
                $table->dropColumn('manual_created_at');
            });
        }
    }
}
