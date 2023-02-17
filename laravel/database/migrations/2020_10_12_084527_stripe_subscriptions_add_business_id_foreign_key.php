<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StripeSubscriptionsAddBusinessIdForeignKey extends Migration
{
    use MigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $constraints = collect($this->getDbTableColumnConstraints('business', 'id'));
        if(!$constraints->where('CONSTRAINT_NAME', 'stripe_subscriptions_business_id_foreign')->count()) {
            Schema::table('stripe_subscriptions', function (Blueprint $table) {
                $table->foreign('business_id')
                    ->references('id')->on('business')
                    ->onDelete('cascade');
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
        Schema::table('stripe_subscriptions', function (Blueprint $table) {
            $table->dropForeign('business_id');
        });
    }
}
