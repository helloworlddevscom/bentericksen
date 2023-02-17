<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBusinessPolicyUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_policy_updates', function (Blueprint $table) {
            $table->integer('business_id');
            $table->integer('policy_updater_id');
            $table->timestamp('accepted_at')->nullable();
            $table->text('policies')->nullable();
            $table->timestamps();
        });

        // this section of the migration is to reset the current policy updates.
        DB::table('user_policy_updates')->truncate();
        DB::table('policy_updater')->truncate();
        DB::table('policy_template_updates')->truncate();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_policy_updates');
    }
}
