<?php

use App\Business;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBusinessTableFixPostalCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $businesses = Business::whereRaw('length(postal_code) < 5')->get();
        foreach ($businesses as $business) {
            $business->postal_code = '0'.$business->postal_code;
            $business->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
