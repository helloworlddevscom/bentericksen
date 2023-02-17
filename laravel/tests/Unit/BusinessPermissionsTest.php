<?php

namespace Tests\Unit;

use App\BusinessPermission;
use App\User;
use Tests\SeededTestCase;

class BusinessPermissionsTest extends SeededTestCase
{
    /**
     * Tests the permissions() method on the User model.
     * @return void
     * @group business_operation
     */
    public function testPermissions()
    {
        // Note: fixture data is stored in the database seeds and is loaded in the setUp() method of the parent class

        // manager (with default permissions)
        $user = User::find(102);
        $this->assertTrue($user->permissions('m100'));
        $this->assertEquals('View/Edit', $user->permissions('m120'));
        $this->assertEquals('Full Access', $user->permissions('m180'));

        // change some permissions and recheck manager
        $permissions = BusinessPermission::where('business_id', 1)->first();
        $permissions->update([
            'm100' => 0,
            'm180' => 0,
        ]);

        $this->assertFalse($user->permissions('m100'));
        $this->assertEquals('No Access', $user->permissions('m180'));

        $permissions = BusinessPermission::where('business_id', 1)->first();
        $permissions->update([
            'm120' => 2,
            'm180' => 2,
        ]);
        $this->assertEquals('View Only', $user->permissions('m120'));
        $this->assertEquals('Print Only', $user->permissions('m180'));

        // admin (should still have permissions to the things the manager can't do)
        $user = User::find(1);
        $this->assertTrue($user->permissions('m100'));
        $this->assertEquals('View/Edit', $user->permissions('m120'));
        $this->assertEquals('Full Access', $user->permissions('m180'));

        // owner (should still have permissions to the things the manager can't do)
        $user = User::find(101);
        $this->assertTrue($user->permissions('m100'));
        $this->assertEquals('View/Edit', $user->permissions('m120'));
        $this->assertEquals('Full Access', $user->permissions('m180'));

        // TODO: test employee here as well
    }
}
