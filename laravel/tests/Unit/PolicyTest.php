<?php

namespace Tests\Unit;

use App\Business;
use App\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Container for the objects created in the setUp() method.
     * @var array
     * @group policy
     */
    private $fixtures = [];

    /**
     * Setup method before tests. Here is where we can create common fixture
     * data that can be used by any test method in this class.
     * @group policy
     */
    protected function setUp(): void
    {
        parent::setUp();

        // here we set up some data fixtures that can be used by all the tests

        $this->fixtures['business'][1] = factory(Business::class)->create(['primary_user_id' => 1, 'primary_role' => 'owner']);
        $this->fixtures['business'][2] = factory(Business::class)->create(['primary_user_id' => 2, 'finalized' => 1, 'primary_role' => 'owner']);

        // user roles
        $roles = [1 => 'admin', 2 => 'owner', 3 => 'manager', 4 => 'consultant', 5 => 'employee'];
        $this->resetRoles();
        $this->resetUsers();
        foreach ($roles as $id => $name) {
            $role = new Role;
            $role->id = $id;
            $role->name = $name;
            $role->save();

            // also create a user with this role
            $this->fixtures['user'][$id] = factory(\App\User::class)->create([
                'id' => $id,
                // it doesn't matter what business the user is in for this test. The value just can't be null.
                'business_id' => $this->fixtures['business'][1]->id,
                'email' => 'user'.$id.'@example.com',
            ]);
            $this->fixtures['user'][$id]->roles()->attach($id);
        }

        // there is also one more user with no roles
        $this->fixtures['user'][6] = factory(\App\User::class)->create([
            'id' => 6,
            'business_id' => $this->fixtures['business'][1]->id,
            'email' => 'user6@example.com',
        ]);
    }

    /**
     * Tests whether the user can edit the title of a required policy.
     *
     * @return void
     * @group policy
     */
    public function testIsPolicyTitleEditable()
    {
        $policy = factory(\App\Policy::class)->create(['business_id'=>$this->fixtures['business'][1]->id, 'content' => 'test', 'content_raw' => 'test', 'special' => '', 'special_extra' => '']);

        $this->assertEquals(0, $this->fixtures['business'][1]->finalized);
        $this->assertEquals(1, $this->fixtures['business'][2]->finalized);

        // required policy - only admins should be able to edit
        $policy->requirement = 'required';
        $this->assertTrue($policy->userCanEdit($this->fixtures['user'][1]));
        $this->assertFalse($policy->userCanEdit($this->fixtures['user'][2]));
        $this->assertFalse($policy->userCanEdit($this->fixtures['user'][3]));
        $this->assertFalse($policy->userCanEdit($this->fixtures['user'][4]));  // consultants can't edit either, per Alan 2019-03-28
        $this->assertFalse($policy->userCanEdit($this->fixtures['user'][5]));
        $this->assertFalse($policy->userCanEdit($this->fixtures['user'][6]));

        // optional policy - everyone but employees should be able to edit
        $policy->requirement = 'optional';
        $this->assertTrue($policy->userCanEdit($this->fixtures['user'][1]));
        $this->assertTrue($policy->userCanEdit($this->fixtures['user'][2]));
        $this->assertTrue($policy->userCanEdit($this->fixtures['user'][3]));
        $this->assertTrue($policy->userCanEdit($this->fixtures['user'][4]));
        $this->assertFalse($policy->userCanEdit($this->fixtures['user'][5]));
        $this->assertFalse($policy->userCanEdit($this->fixtures['user'][6]));
    }
}
