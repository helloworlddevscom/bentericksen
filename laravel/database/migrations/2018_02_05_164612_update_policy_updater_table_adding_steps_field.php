<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdatePolicyUpdaterTableAddingStepsField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('policy_updater', function (Blueprint $table) {
            $table->integer('step')->after('emails')->nullable();
            $table->boolean('is_finalized')->after('step')->default(0);
        });

        \DB::table('policy_updater')->whereNotNull('title')->update(['is_finalized' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('policy_updater', 'step')) {
            Schema::table('policy_updater', function (Blueprint $table) {
                $table->dropColumn('step');
            });
        }

        if (Schema::hasColumn('policy_updater', 'is_finalized')) {
            Schema::table('policy_updater', function (Blueprint $table) {
                $table->dropColumn('is_finalized');
            });
        }
    }
}
