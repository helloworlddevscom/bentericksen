<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePolicyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policy_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->integer('category_id')->nullable();
            $table->string('admin_name');
            $table->string('manual_name');
            $table->string('benefit_type')->nullable();

            $table->timestamp('effective_date')->nullable();
            $table->timestamp('end_date')->nullable();

            $table->text('state')->nullable();
            $table->integer('min_employee')->nullable();
            $table->integer('max_employee')->nullable();

            $table->string('requirement')->nullable();
            $table->string('parameters')->nullable();

            $table->string('tags')->default('[]');

            $table->timestamps();
            $table->string('status')->default('open');
            $table->integer('order')->default(0);
        });

        Schema::create('policies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->integer('template_id')->unsigned()->nullable();
            $table->string('manual_name');
            $table->text('content');
            $table->text('content_raw');
            $table->string('status')->default('enabled');
            $table->integer('order')->default(0);
            $table->string('requirement')->default('optional');
            $table->string('tags')->default('[]');
            $table->string('edited', 5)->default('no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('policy_templates');
        Schema::drop('policies');
    }
}
