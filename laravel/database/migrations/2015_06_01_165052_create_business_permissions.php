<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBusinessPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id');
            //#Manager
            //access
            $table->boolean('m100')->default(1);

            //policy
            $table->integer('m120')->default(1);
            $table->integer('m121')->default(1);

            //employees
            $table->integer('m140')->default(1);
            $table->integer('m141')->default(1);
            $table->integer('m142')->default(1);
            $table->integer('m143')->default(1);
            $table->integer('m144')->default(1);
            $table->integer('m145')->default(1);
            $table->integer('m146')->default(1);
            $table->integer('m147')->default(1);
            $table->integer('m148')->default(1);

            //job descriptions
            $table->integer('m160')->default(1);

            //forms
            $table->integer('m180')->default(1);
            $table->integer('m181')->default(1);

            //tools and reports
            $table->integer('m200')->default(1);
            $table->integer('m201')->default(1);
            $table->integer('m202')->default(1);

            //other services
            $table->integer('m220')->default(1);
            $table->integer('m221')->default(1);
            $table->integer('m222')->default(1);
            $table->integer('m223')->default(1);

            //dashboard
            $table->integer('m240')->default(1);

            //account and settings
            $table->integer('m260')->default(1);
            $table->integer('m261')->default(1);
            $table->integer('m262')->default(1);

            //#Employee
            //access
            $table->boolean('e100')->default(1);

            //policies
            $table->integer('e120')->default(1);
            $table->integer('e121')->default(1);

            //Benefits
            $table->integer('e140')->default(1);
            $table->integer('e141')->default(1);
            $table->integer('e142')->default(1);

            //Job Description
            $table->integer('e160')->default(1);

            //forms
            $table->integer('e180')->default(1);
            $table->integer('e181')->default(1);
            $table->integer('e182')->default(1);

            //time off / leave requests
            $table->integer('e200')->default(1);
            $table->integer('e201')->default(1);

            //My Info
            $table->integer('e221')->default(1);
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
        Schema::drop('business_permissions');
    }
}
