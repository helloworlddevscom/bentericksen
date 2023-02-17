<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMinMaxEmployeesToFormTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_templates', function (Blueprint $table) {
          $table->integer('min_employee')->nullable();
          $table->integer('max_employee')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_templates', function (Blueprint $table) {
          $table->dropColumn('min_employee');
          $table->dropColumn('max_employee');
        });
    }
}
