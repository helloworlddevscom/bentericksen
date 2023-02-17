<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePolicyTemplateUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policy_template_updates', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->integer('category_id')->nullable();

            $table->string('admin_name');
            $table->string('manual_name');
            $table->string('alternate_name');
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

            $table->integer('template_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('policy_template_updates');
    }
}
