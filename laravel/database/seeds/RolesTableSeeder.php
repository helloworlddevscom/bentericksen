<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'id'           => 1,
                'name'         => 'admin',
                'display_name' => 'Administrator',
            ],
            [
                'id'           => 2,
                'name'         => 'owner',
                'display_name' => 'Owner',
            ],
            [
                'id'           => 3,
                'name'         => 'manager',
                'display_name' => 'Manager',
            ],
            [
                'id'           => 4,
                'name'         => 'consultant',
                'display_name' => 'Consultant',
            ],
            [
                'id'           => 5,
                'name'         => 'employee',
                'display_name' => 'Employee',
            ],
        ]);
    }
}
