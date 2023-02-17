<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersAddingForeignKeysToRelationships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_licensure_certifications', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('salaries', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('emergency_contact', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('user_attendance', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('timeoff_requests', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('users_paperwork', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->change();
            $table->integer('paperwork_id')->unsigned()->change();
            // Removed these when setting up unit test database because they're
            // duplicates of the ones in 2015_12_11_215422_create_paperwork_table.php - KD
//            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
//            $table->foreign('paperwork_id')->references('id')->on('paperwork')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_licensure_certifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('salaries', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('emergency_contact', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_attendance', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('timeoff_requests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('users_paperwork', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'paperwork_id']);
        });
    }
}
