<?php

namespace Tests\Unit;

use App\Business;
use App\Role;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BusinessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the business users.
     *
     * @return void
     * @group business_operation
     */
    public function testBusinessUsers()
    { 
        $this->resetRoles();
        $this->resetUsers();
        // a consultant
        factory(User::class)->create(['id' => 1]);

        // a regular user
        $user101 = factory(User::class)->create([
            'id' => 101,
            'business_id' => 1,
        ]);

        $roles = [2 => 'owner', 3 => 'manager', 4 => 'consultant'];
        
        foreach ($roles as $id => $name) {
            $role = new Role;
            $role->id = $id;
            $role->name = $name;
            $role->save();
        }

        // the business
        $business1 = factory(Business::class)->create([
            'primary_user_id' => 101,
            'primary_role' => 'owner',
            'secondary_1_first_name' => 'Alice',
            'secondary_1_last_name' => 'Smith',
            'secondary_1_email' => 'alice@example.com',
            'consultant_user_id' => 1,
        ]);

        $business2 = factory(Business::class)->create([
            'primary_user_id' => 103,
            'primary_role' => 'owner',
            'secondary_1_first_name' => 'Alice',
            'secondary_1_last_name' => 'Smith',
            'secondary_1_email' => 'alice@example.com',
            'consultant_user_id' => 1,
        ]);

        $manager1 = factory(User::class)->create([
            'id' => 102,
            'business_id' => 1,
        ]);

        $manager1->roles()->attach(3);

        $manager1->accepted_terms = '0000-00-00 00:00:00';

        // the first assertions help to validate that the factory logic above is correct
        $this->assertEquals(101, $business1->primary_user_id);
        $this->assertCount(2, $business1->users);
        $this->assertDatabaseHas('users', ['id' => 101]);

        //check to see which businesses has managers:
        $this->assertEquals(1, count($business1->getManagers()));
        $this->assertEquals(0, count($business2->getManagers()));
        // verify terms are accepted.
        $this->assertEquals(true, $business1->termsAccepted());

        // test the list of business users (array of names and email addresses)
        $expectedPrimary = ['id' => 101, 'full_name' => $user101->fullName, 'email' => $user101->email];
        $this->assertEquals($expectedPrimary, $business1->getPrimaryUser());

        $expectedSecondary = [
            ['full_name' => 'Alice Smith', 'email' => 'alice@example.com'],
        ];
        $this->assertEquals($expectedSecondary, $business1->getSecondaryUsersInfo());

        // the whole list of business users is the primary + any secondaries
        // (but does not include any consultants)
        $expectedAll = [$expectedPrimary, $expectedSecondary[0]];
        $this->assertEquals($expectedAll, $business1->getBusinessUsers());

        // the consultant is separate
        $this->assertEquals(1, $business1->getConsultant()->id);

        // the business' contact name is the full name of the primary user
        $this->assertEquals($user101->fullName, $business1->getContactNameAttribute());

        // whether to notify the consultant
        $this->assertTrue($business1->notifyConsultant());
        $business1->finalized = true;
        $this->assertFalse((bool) $business1->notifyConsultant());
        $business1->ongoing_consultant_cc = true;
        $this->assertTrue($business1->notifyConsultant());

        // the special version of the status string used with policy update emails
        $business1->status = 'active';
        $this->assertEquals('active', $business1->getStatusForUpdateEmails());
        $business1->status = 'renewed';
        $this->assertEquals('active', $business1->getStatusForUpdateEmails());
        $business1->status = 'expired';
        $this->assertEquals('inactive', $business1->getStatusForUpdateEmails());
        $business1->status = 'cancelled';
        $this->assertEquals('inactive', $business1->getStatusForUpdateEmails());
        // unknown statuses should translate to 'inactive' (this should never happen in the real world)
        $business1->status = 'some-other-status';
        $this->assertEquals('inactive', $business1->getStatusForUpdateEmails());
    }
}
