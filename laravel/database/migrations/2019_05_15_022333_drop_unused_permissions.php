<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnusedPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // remove some columns from the business_permissions table that were not used.
        Schema::table('business_permissions', function (Blueprint $table) {
            $table->dropColumn([
                'm141', 'm142', 'm143', 'm146', 'm147', 'm181',
                'm202', 'm220', 'm221', 'm222', 'm223', 'm261', 'm262',
                'e121', 'e140', 'e141', 'e142', 'e180', 'e181', 'e182', 'e201',
            ]);
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
            $table->integer('m141')->default(1);
            $table->integer('m142')->default(1);
            $table->integer('m143')->default(1);
            $table->integer('m146')->default(1);
            $table->integer('m147')->default(1);
            $table->integer('m181')->default(1);
            $table->integer('m202')->default(1);
            $table->integer('m220')->default(1);
            $table->integer('m221')->default(1);
            $table->integer('m222')->default(1);
            $table->integer('m223')->default(1);
            $table->integer('m261')->default(1);
            $table->integer('m262')->default(1);
            $table->integer('e121')->default(1);
            $table->integer('e140')->default(1);
            $table->integer('e141')->default(1);
            $table->integer('e142')->default(1);
            $table->integer('e180')->default(1);
            $table->integer('e181')->default(1);
            $table->integer('e182')->default(1);
            $table->integer('e201')->default(1);
        });
    }
}
