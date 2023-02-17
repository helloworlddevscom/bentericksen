<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManagerPaymentPermissionToBusinessPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_permissions', function (Blueprint $table) {
            $table->integer('m280')
                ->after('m260')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_permissions', function (Blueprint $table) {
            $table->dropColumn('m280');
        });
    }
}
