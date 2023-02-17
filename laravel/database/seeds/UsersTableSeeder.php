<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // global admin
        $id = DB::table('users')->insertGetId([
            'email' => 'admin@example.com',
            'password' => bcrypt('fff'),
            'status' => 'enabled',
            'first_name' => 'Bent',
            'last_name' => 'Ericksen',
            'included_in_employee_count' => 1,
            'can_access_system' => 1,
        ]);

        DB::table('role_user')->insert([
            'user_id' => $id,
            'role_id' => 1,
        ]);

        // users for the first Business
        DB::table('users')->insert([
            [
                'id' => 101,
                'business_id' => 1,
                'email' => 'owner1@example.com',
                'password' => bcrypt('fff'),
                'status' => 'enabled',
                'first_name' => 'Owner',
                'last_name' => 'One',
                'included_in_employee_count' => 1,
                'can_access_system' => 1,
            ],
            [
                'id' => 102,
                'business_id' => 1,
                'email' => 'manager1@example.com',
                'password' => bcrypt('fff'),
                'status' => 'enabled',
                'first_name' => 'Manager',
                'last_name' => 'One',
                'included_in_employee_count' => 1,
                'can_access_system' => 1,
            ],
            [
                'id' => 103,
                'business_id' => 1,
                'email' => 'employee1@example.com',
                'password' => bcrypt('fff'),
                'status' => 'enabled',
                'first_name' => 'Employee',
                'last_name' => 'One',
                'included_in_employee_count' => 1,
                'can_access_system' => 1,
            ],
            [
                'id' => 104,
                'business_id' => 1,
                'email' => 'consultant1@example.com',
                'password' => bcrypt('fff'),
                'status' => 'enabled',
                'first_name' => 'Employee',
                'last_name' => 'One',
                'included_in_employee_count' => 1,
                'can_access_system' => 1,
            ]
        ]);

        DB::table('role_user')->insert([
            ['user_id' => 101, 'role_id' => 2, ],  // owner
            ['user_id' => 102, 'role_id' => 3, ],  // manager
            ['user_id' => 104, 'role_id' => 4, ],  // consultant
            ['user_id' => 103, 'role_id' => 5, ]  // employee
        ]);
    }
}
