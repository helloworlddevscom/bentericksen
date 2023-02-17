<?php

namespace Tests;

use App\Business;
use App\BusinessAsas;
use App\BusinessPermission;
use App\Role;
use App\TimeOff;
use App\User;
use DB;

/**
 * Class SeededTestCase.
 *
 * Parent class for unit tests that need the standard seed data (used as fixtures in tests). This is faster
 * than using the RefreshDatabase trait, which runs all the migrations.
 */
class SeededTestCase extends TestCase
{
    /**
     * Setup method before tests. Here is where we can create common fixture
     * data that can be used by any test method in this class.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // clear data from previous tests (faster than using RefreshDatabase)
        DB::statement('SET foreign_key_checks=0');
        User::truncate();
        Role::truncate();
        DB::table('role_user')->truncate();
        Business::truncate();
        BusinessPermission::truncate();
        BusinessAsas::truncate();
        TimeOff::truncate();
        DB::statement('SET foreign_key_checks=1');

        // load in fixture data from the database seeds
        $this->artisan('db:seed');
    }
}
