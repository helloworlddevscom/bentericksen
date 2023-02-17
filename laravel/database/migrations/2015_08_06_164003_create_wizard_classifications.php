<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWizardClassifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wizard_classifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id');
            $table->integer('classification_id');
            $table->string('benefit_type');
            $table->text('settings');
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
        Schema::drop('wizard_classifications');
    }
}
