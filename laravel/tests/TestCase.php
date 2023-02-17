<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use DB;
use App\Role;
use App\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function resetRoles()
    {
      DB::statement('SET foreign_key_checks=0');
      DB::table('role_user')->truncate();
      Role::truncate();
    }

    protected function resetUsers()
    {
      DB::statement('SET foreign_key_checks=0');
      User::truncate();
    }

}
