<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBusinessTableAddBonusproFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business', function (Blueprint $table) {
            $table->boolean('hrdirector_enabled')->unsigned()->default(0);
            $table->boolean('bonuspro_enabled')->unsigned()->default(0);
            $table->timestamp('bonuspro_expiration_date')->nullable();
        });

        DB::table('business')->update(['hrdirector_enabled' => 1, 'updated_at' => now()]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business', function (Blueprint $table) {
            $table->dropColumn('hrdirector_enabled');
            $table->dropColumn('bonuspro_enabled');
            $table->dropColumn('bonuspro_expiration_date');
        });
    }
}
