<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateLicensureCertificationsTableCprRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('licensure_certifications')
            ->where('name', 'CPR')
            ->update(['type' => 'dental']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('licensure_certifications')
            ->where('name', 'CPR')
            ->update(['type' => null]);
    }
}
