<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmployeePermissionsForStreamdent extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('business_permissions', function (Blueprint $table) {
            $table->integer('e230')
                ->default(1);
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
            $table->dropColumn('e230');
        });
    }
}
