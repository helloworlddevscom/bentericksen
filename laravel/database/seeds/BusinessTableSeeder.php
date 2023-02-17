<?php

use Illuminate\Database\Seeder;

class BusinessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('business')->updateOrInsert([
            'id' => 1,
            'name' => 'aa_template Test Business',
            'primary_user_id' => 101,
            'primary_role' => 'owner',
            'finalized' => 1,
            'additional_employees' => 10,
            'hrdirector_enabled' => 1,
            'bonuspro_enabled' => 1,
            'bonuspro_expiration_date' => '2030-12-31 00:00:00',
            'status' => 'active',
            'address1' => '1234 Test Ave.',
            'city' => 'Portland',
            'state' => 'OR',
            'postal_code' => '97035',
            'phone1' => '111-222-3333',
            'type' => 'dental',
            'subtype' => 'general'
        ]);

        DB::table('business_asas')->insert([
            'business_id' => 1,
            'type' => 'annual-1-14',
            'expiration' => '2030-12-31 00:00:00',
            'status' => 'active',
        ]);

        DB::table('business_permissions')->insert([
            'business_id' => 1,
            'm100' => 1,
            'm101' => 1,
            'm120' => 1,
            'm121' => 1,
            'm140' => 1,
            'm144' => 1,
            'm145' => 1,
            'm148' => 1,
            'm160' => 1,
            'm180' => 1,
            'm200' => 1,
            'm201' => 1,
            'm240' => 1,
            'm260' => 1,
            'e100' => 1,
            'e120' => 1,
            'e160' => 1,
            'e200' => 1,
            'e221' => 1,
        ]);
    }
}
