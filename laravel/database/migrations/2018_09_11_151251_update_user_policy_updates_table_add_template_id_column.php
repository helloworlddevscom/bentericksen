<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserPolicyUpdatesTableAddTemplateIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_policy_updates', function (Blueprint $table) {
            $table->unsignedInteger('policy_template_update_id')->after('policy_updater_id');
        });

        Schema::table('business_policy_updates', function (Blueprint $table) {
            $table->unsignedInteger('policy_template_update_id')->after('policy_updater_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_policy_updates', function (Blueprint $table) {
            $table->dropColumn('policy_template_update_id');
        });

        Schema::table('business_policy_updates', function (Blueprint $table) {
            $table->dropColumn('policy_template_update_id');
        });
    }
}
