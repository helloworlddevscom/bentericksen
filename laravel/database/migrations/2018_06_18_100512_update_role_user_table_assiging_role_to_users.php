<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRoleUserTableAssigingRoleToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // getting users that does not have a role assigned
        $ids = DB::table('role_user')->distinct()->pluck('user_id');
        $user_ids = DB::table('users')->whereNotIn('id', $ids)->get()->pluck('id');

        // assigning employee role to all those users
        $rows = [];
        foreach ($user_ids as $user_id) {
            array_push($rows, ['user_id' => $user_id, 'role_id' => 5]);
        }

        // inserting rows in the DB
        DB::table('role_user')->insert($rows);
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
