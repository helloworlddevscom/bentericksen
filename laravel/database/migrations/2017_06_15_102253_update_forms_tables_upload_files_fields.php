<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateFormsTablesUploadFilesFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        Schema::table('form_templates', function (Blueprint $table) {
            $table->string('file_name', 255)->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->text('description')->nullable(0)->change();
        });

        Schema::table('form_templates', function (Blueprint $table) {
            $table->dropColumn('file_name');
        });
    }
}
