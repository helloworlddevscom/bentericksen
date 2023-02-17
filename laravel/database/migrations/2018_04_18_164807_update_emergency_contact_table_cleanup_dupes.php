<?php

use App\EmergencyContact;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmergencyContactTableCleanupDupes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = User::with(['emergencyContacts' => function ($q) {
            // selecting only contacts non-primary and where the name is blank
            $q->where('is_primary', '=', 0);
            $q->where('name', '=', '');
        }])->get();

        foreach ($users as $user) {
            if ($user->emergencyContacts->count() <= 1) {
                continue;
            }
            // deleting extra record for each user.
            $user->emergencyContacts->last()->delete();
        }

        // updating only the records where the relationship is "cell"
        DB::statement("UPDATE emergency_contact set relationship = '' where relationship = 'cell'");
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
