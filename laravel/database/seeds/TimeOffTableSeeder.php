<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TimeOffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('timeoff_requests')->insert([
            // future, pending
            [
                'user_id' => 103,
                'type' => 'Time Off',
                'request_type' => 'timeoff',
                'status' => 'pending',
                'start_at' => Carbon::now()->addDays(1),
                'end_at' => Carbon::now()->addDays(1),
                'created_at' => Carbon::now()->addMinutes(-1),
            ],
            // future, approved
            [
                'user_id' => 103,
                'type' => 'Time Off',
                'request_type' => 'timeoff',
                'status' => 'approved',
                'start_at' => Carbon::now()->addDays(2),
                'end_at' => Carbon::now()->addDays(2),
                'created_at' => Carbon::now()->addMinutes(-2),
            ],
            // future, denied
            [
                'user_id' => 103,
                'type' => 'Time Off',
                'request_type' => 'timeoff',
                'status' => 'denied',
                'start_at' => Carbon::now()->addDays(3),
                'end_at' => Carbon::now()->addDays(3),
                'created_at' => Carbon::now()->addMinutes(-3),
            ],
            // past
            [
                'user_id' => 103,
                'type' => 'Time Off',
                'request_type' => 'timeoff',
                'status' => 'approved',
                'start_at' => Carbon::now()->addDays(-2),
                'end_at' => Carbon::now()->addDays(-2),
                'created_at' => Carbon::now()->addMinutes(-4),
            ],
        ]);
    }
}
