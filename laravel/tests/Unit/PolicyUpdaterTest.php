<?php

namespace Tests\Unit;

use App\Business;
use App\Role;
use Bentericksen\PolicyUpdater\PolicyUpdater;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyUpdaterTest extends TestCase
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
     *
     * Fixture data is long and boring, so we don't want PHP Mess Detector bothering us.
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @group policy
     *
     * @todo Find a better data fixture manager (some day)
     */
    protected function setUp(): void
    {
        parent::setUp();

        // here we set up some data fixtures that can be used by all the tests

        // user roles
        $roles = [2 => 'owner', 3 => 'manager', 4 => 'consultant'];
        $this->resetRoles();
        $this->resetUsers();
        foreach ($roles as $id => $name) {
            $role = new Role;
            $role->id = $id;
            $role->name = $name;
            $role->save();
        }

        // Businesses (and their associated Service Agreements)

        // Note: we refer to the businesses as 1, 2, 3, 4... here but their
        // actual IDs in the database might be different.

        $this->fixtures['business'][1] = factory(Business::class)->create([
            'primary_user_id' => 10,
            'primary_role' => 'owner',
            'secondary_1_first_name' => 'Alice',
            'secondary_1_last_name' => 'Smith',
            'secondary_1_email' => 'alice@example.com',
            'consultant_user_id' => 1,
            'state' => 'OR',
        ]);

        // business in "renewed" status (should still receive email)
        $this->fixtures['business'][2] = factory(Business::class)->create([
            'primary_user_id' => 20,
            'consultant_user_id' => 2,
            'primary_role' => 'owner',
            'status' => 'renewed',
            'state' => 'OR',
        ]);

        // business in a different state
        $this->fixtures['business'][3] = factory(Business::class)->create([
            'primary_user_id' => 30,
            'primary_role' => 'owner',
            'state' => 'CA',
        ]);

        // business that does have the policy, but is "do not contact"
        $this->fixtures['business'][4] = factory(Business::class)->create([
            'primary_user_id' => 40,
            'primary_role' => 'owner',
            'do_not_contact' => 1,
            'state' => 'OR',
        ]);

        // business that does have the policy, is "finalized" (a.k.a. "the switch") and has
        // "ongoing consultant" checked (should receive email)
        $this->fixtures['business'][5] = factory(Business::class)->create([
            'primary_user_id' => 50,
            'consultant_user_id' => 5,
            'primary_role' => 'owner',
            'finalized' => 1,
            'ongoing_consultant_cc' => 1,
            'state' => 'OR',
        ]);

        // business that does have the policy, is "finalized" (a.k.a. "the switch") and *does not* have
        // the "ongoing consultant" checked (should *not* receive email)
        $this->fixtures['business'][6] = factory(Business::class)->create([
            'primary_user_id' => 60,
            'primary_role' => 'owner',
            'consultant_user_id' => 6,
            'finalized' => 1,
            'ongoing_consultant_cc' => 0,
            'state' => 'OR',
        ]);

        // a business that is owned by one of the consultants
        $this->fixtures['consultant_business'] = factory(Business::class)->create([
            'primary_user_id' => 1,
            'primary_role' => 'consultant',
            'state' => 'NV',
        ]);

        factory(\App\BusinessAsas::class)->create(['business_id' => $this->fixtures['consultant_business']->id]);

        // Consultants
        $this->fixtures['consultant'][1] = factory(\App\User::class)->create([
            'id' => 1,
            // This consultant has for a special business, of which they are the owner
            // (this is common in the production database, usually with a demo business)
            'business_id' => $this->fixtures['consultant_business']->id,
            'email' => 'consultant1@example.com',
        ]);
        $this->fixtures['consultant'][1]->roles()->attach(2);  // owner
        $this->fixtures['consultant'][1]->roles()->attach(4);  // and consultant

        $this->fixtures['consultant'][2] = factory(\App\User::class)->create([
            'id' => 2,
            // note: this consultant has a NULL business ID
            'business_id' => null,
            'email' => 'consultant2@example.com',
        ]);
        $this->fixtures['consultant'][2]->roles()->attach(4);

        // there's no consultant for business 3 and 4

        $this->fixtures['consultant'][5] = factory(\App\User::class)->create([
            'id' => 5,
            'business_id' => $this->fixtures['consultant_business']->id,
            'email' => 'consultant5@example.com',
        ]);
        $this->fixtures['consultant'][5]->roles()->attach(4);

        $this->fixtures['consultant'][6] = factory(\App\User::class)->create([
            'id' => 6,
            'business_id' => $this->fixtures['consultant_business']->id,
            'email' => 'consultant6@example.com',
        ]);
        $this->fixtures['consultant'][6]->roles()->attach(4);

        // Policy Templates
        $this->fixtures['template1'] = factory(\App\PolicyTemplate::class)->create([
            'state' => '["OR"]',
            'admin_name' => 'Template 1',
        ]);

        // Users and policies for each Business
        foreach ($this->fixtures['business'] as $index => $biz) {

            // Service Agreements
            factory(\App\BusinessAsas::class)->create(['business_id' => $biz->id]);

            // Permissions
            factory(\App\BusinessPermission::class)->create(['business_id' => $biz->id]);

            // Owners
            $owner_id = $index * 10;
            $this->fixtures['user'][$owner_id] = factory(\App\User::class)->create([
                'id' => $owner_id,
                'business_id' => $biz->id,
                'email' => 'user'.$owner_id.'@example.com',
            ]);
            $this->fixtures['user'][$owner_id]->roles()->attach(2);

            // Policies (the business-specific versions, not PolicyTemplates)
            // no policies for business 3
            if ($biz->id != 3) {
                factory(\App\Policy::class)->create([
                    'business_id' => $biz->id,
                    'template_id' => $this->fixtures['template1']->id,
                    'content' => 'test',
                    'content_raw' => 'test',
                    'special' => '',
                    'special_extra' => '',
                ]);
            }
        }
        // note: the consultant business does not have the policy.

        // Managers
        $this->fixtures['user'][21] = factory(\App\User::class)->create([
            'id' => 21,
            'business_id' => $this->fixtures['business'][2]->id,
            'email' => 'user21@example.com',
        ]);
        $this->fixtures['user'][21]->roles()->attach(3);  // 3 = manager
        $this->fixtures['user'][31] = factory(\App\User::class)->create([
            'id' => 31,
            'business_id' => $this->fixtures['business'][3]->id,
            'email' => 'user31@example.com',
        ]);
        $this->fixtures['user'][31]->roles()->attach(3);  // 3 = manager
    }

    /**
     * Test some methods around creating a new PolicyUpdater.
     *
     * @return void
     * @group policy
     */
    public function testPolicyUpdaterConstructor()
    {
        // update in the future
        factory(\App\PolicyTemplateUpdate::class)->create([
            'admin_name' => 'Test - 01',
            'alternate_name' => 'Test - (01)',
        ]);
        // update in the past
        factory(\App\PolicyTemplateUpdate::class)->create([
            'effective_date' => date('Y-m-d', time() - 86400 * 7),
            'admin_name' => 'Test - 02',
            'alternate_name' => 'Test - (02)',
        ]);
        // disabled update
        factory(\App\PolicyTemplateUpdate::class)->create([
            'status' => 'disabled',
            'admin_name' => 'Test - 03',
            'alternate_name' => 'Test - (03)',
        ]);
        // an updater
        $updater_in_database = factory(\App\PolicyUpdater::class)->create([
            'policies' => '{"1":"1"}',
        ]);

        // test the constructor
        $updater = new PolicyUpdater();
        // only 2 of the 3 updates above should appear, based on date and status
        // disabled policies are updated as well as enabled.
        // only polices in the past are not updated
        $this->assertCount(2, $updater->available_policies);

        // test the create() and load() methods
        $updater->create();
        $this->assertEquals(1, $updater->update->step);
        $this->assertEquals('pending', $updater->update->status);

        $request = [
            'step' => 2,
            'additional_emails' => 'aaa@example.com
            bbb@example.com',
            'id' => $updater_in_database->id,
        ];
        $updater->load($request);
        $this->assertEquals(2, $updater->step);
        $this->assertEquals($updater_in_database->id, $updater->update->id);
        $this->assertEquals($updater_in_database->id, $updater->id);

        $this->assertEquals(['aaa@example.com', 'bbb@example.com'], $updater_in_database->contacts()->additional()->pluck('email')->toArray());
    }

    /**
     * Tests that the PolicyUpdater class generates the correct lists of email
     * addresses for the "New Policy Update" form.
     * @group policy
     */
    public function testEmailLists()
    {
      $this->markTestSkipped('must be revisited.');
      
        // we need to create a PolicyTemplateUpdate object for the policies
        // our fixture businesses have.
        factory(\App\PolicyTemplateUpdate::class)->create([
            'template_id' => $this->fixtures['template1']->id,
            'state' => '["OR"]',
            'admin_name' => 'Test 01',
            'alternate_name' => 'Test (01)',
        ]);
        // and a PolicyUpdater
        $updater_in_database = factory(\App\PolicyUpdater::class)->create([
            'additional_emails' => '["aaa@example.com", "bbb@example.com"]',
            'policies' => json_encode([$this->fixtures['template1']->id => 1]),
        ]);

        $this->assertDatabaseHas('business_asas', ['business_id' => $this->fixtures['business'][1]->id]);
        $this->assertDatabaseHas('business', ['id' => $this->fixtures['business'][1]->id]);
        $this->assertDatabaseHas('business', ['id' => $this->fixtures['business'][2]->id]);
        $this->assertDatabaseHas('users', ['id' => 10]);
        $this->assertDatabaseHas('users', ['id' => 20]);
        $this->assertDatabaseHas('policy_templates', ['id' => $this->fixtures['template1']->id]);

        // validating that the JSON data in the PolicyTemplate is encoded correctly
        $template = \App\PolicyTemplate::find($this->fixtures['template1']->id);
        $this->assertEquals(['required'], $template->requirement);

        $b1 = Business::find($this->fixtures['business'][1]->id);
        $this->assertTrue($b1->isEligibleForUpdateNotifications(Carbon::now()));

        // we should now be able to test the actual email lists
        $updater = new PolicyUpdater();
        $updater->load([
            'id' => $updater_in_database->id,
            'step'=>2,
            'policies' => [$this->fixtures['template1']->id => '1'],
        ]);

        $emails = $updater->getEmailLists(0);

        $this->assertArrayHasKey('list', $emails);
        $expected = [

            // First sub-list: secondary contacts

            // only business 1 has a secondary contact
            ['role' => 'secondary', 'status' => 'active', 'email' => 'alice@example.com'],

            // Second sub-list: consultants

            // Business 1
            ['role' => 'consultant', 'status' => 'active', 'email' => $this->fixtures['consultant'][1]->email],
            // Business 2
            ['role' => 'consultant', 'status' => 'active', 'email' => $this->fixtures['consultant'][2]->email],
            // Business 3 and 4 - no consultants for these
            // Business 5 (finalized + ongoing_consultant_cc) - we want the consultant here
            ['role' => 'consultant', 'status' => 'active', 'email' => $this->fixtures['consultant'][5]->email],
            // Business 6 (finalized, no ongoing_consultant_cc) - no consultant

            // Third sub-list: owners

            // Business 1
            ['role' => 'primary', 'status' => 'active', 'email' => $this->fixtures['user'][10]->email],

            // Business 2: owner + manager
            ['role' => 'primary', 'status' => 'active', 'email' => $this->fixtures['user'][20]->email],
            ['role' => 'primary', 'status' => 'active', 'email' => $this->fixtures['user'][21]->email],

            // business #3 should not appear because it's in a different state.

            // business #4 has "do not contact" set so it shouldn't appear.

            // business #5 and #6 should always have their owners in the list
            // (but not always the consultants - see above)
            ['role' => 'primary', 'status' => 'active', 'email' => $this->fixtures['user'][50]->email],
            ['role' => 'primary', 'status' => 'active', 'email' => $this->fixtures['user'][60]->email],
        ];
        $this->assertEquals($expected, $emails['list']);

        $this->assertArrayHasKey('other_list', $emails);
        // other users who aren't on the recipient list by default are put in the
        // 'other' list so they are available to be added manually
        $expected = [
            // any users listed in the regular $list should not appear here.
            // including consultant #6
            ['role' => 'consultant', 'status' => 'active', 'email' => $this->fixtures['consultant'][6]->email],
            // also including business #3 owner + manager
            ['role' => 'primary', 'status' => 'active', 'email' => $this->fixtures['user'][30]->email],
            ['role' => 'primary', 'status' => 'active', 'email' => $this->fixtures['user'][31]->email],
            // business #4 has "do not contact" set so it shouldn't appear.
        ];
        $this->assertEquals($expected, $emails['other_list']);
    }
}
