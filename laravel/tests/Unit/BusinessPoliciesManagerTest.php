<?php

namespace Tests\Unit;

use App\Business;
use App\Policy;
use App\PolicyTemplate;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BusinessPoliciesManagerTest extends TestCase
{
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
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Delete fixture data from previous runs. This is faster than using RefreshDatabase.
        PolicyTemplate::where('id', '>=', 1)->delete();
        User::where('id', '>=', 1)->delete();

        // Policy Templates - not using factories here because we need to set the IDs
        $this->fixtures['template1'] = PolicyTemplate::create([
            'id' => 1,
            'category_id' => 1,
            'admin_name' => 'Template 1',
            'manual_name' => 'Template 1',
            'content' => '',
            'effective_date' => date('Y-m-d', time() - 86400),
            'min_employee' => null,
            'max_employee' => null,
            'requirement' => ['required'],  // must be array
            'state' => '["ALL"]',
            'status' => 'enabled',
            'content' => 'test',
            'order' => 1,
        ]);
        $this->fixtures['template2'] = PolicyTemplate::create([
            'id' => 2,
            'category_id' => 1,
            'admin_name' => 'Template 2',
            'manual_name' => 'Template 2',
            'content' => '',
            'effective_date' => date('Y-m-d', time() - 86400),
            'min_employee' => 1,
            'max_employee' => 10,
            'requirement' => ['required'],  // must be array
            'state' => '["ALL"]',
            'status' => 'enabled',
            'order' => 2,
        ]);
        $this->fixtures['template3'] = PolicyTemplate::create([
            'id' => 3,
            'category_id' => 1,
            'admin_name' => 'Template 3',
            'manual_name' => 'Template 3',
            'content' => '',
            'effective_date' => date('Y-m-d', time() - 86400),
            'min_employee' => 11,
            'max_employee' => 100,
            'requirement' => ['required'],  // must be array
            'state' => '["ALL"]',
            'status' => 'enabled',
            'order' => 3,
        ]);
        $this->fixtures['template276'] = PolicyTemplate::create([
            'id' => 276,
            'category_id' => 1,
            'admin_name' => 'Template 276',
            'manual_name' => 'Template 276',
            'content' => '',
            'effective_date' => date('Y-m-d', time() - 86400),
            'min_employee' => 1,
            'max_employee' => 10000,
            'requirement' => ['required'],  // must be array
            'state' => '["ALL"]',
            'status' => 'enabled',
            'order' => 4,
        ]);
        $this->fixtures['template277'] = PolicyTemplate::create([
            'id' => 277,
            'category_id' => 1,
            'admin_name' => 'Template 277',
            'manual_name' => 'Template 277',
            'content' => '',
            'effective_date' => date('Y-m-d', time() - 86400),
            'min_employee' => 1,
            'max_employee' => 10000,
            'requirement' => ['required'],  // must be array
            'state' => '["ALL"]',
            'status' => 'enabled',
            'order' => 5,
        ]);

        // users
        $this->fixtures['user101'] = factory(User::class)->create([
            'id' => 101,
            'business_id' => 1,
        ]);
        $this->fixtures['user201'] = factory(User::class)->create([
            'id' => 201,
            'business_id' => 2,
        ]);

        $this->fixtures['user202'] = factory(User::class)->create([
            'id' => 202,
            'business_id' => 2,
        ]);
    }

    /**
     * Tests adding policies for a new business when it is created.
     *
     * @return void
     * @group policy
     * @throws \Exception
     */
    public function testBusinessPolicies()
    {
      $this->markTestSkipped('must be revisited.');
        // some businesses
        $business1 = factory(Business::class)->create(['additional_employees' => 5, 'primary_user_id' => 101, 'primary_role' => 'owner']);
        $business2 = factory(Business::class)->create(['additional_employees' => 55, 'primary_user_id' => 201, 'primary_role' => 'owner']);

        $business1->updatePolicies();
        $policies = $business1->getSortedPolicies(false);

        $this->assertCount(3, $policies);
        $this->assertEquals($this->fixtures['template1']->id, $policies[0]->template_id);
        // this business gets the policy for <= 10 employees
        $this->assertEquals($this->fixtures['template2']->id, $policies[1]->template_id);
        // test handling of stubs
        $this->assertEquals(277, $policies[2]->template_id);
        $this->assertEquals('stub', $policies[2]->special);
        $extra = json_decode($policies[2]->special_extra);
        $this->assertEquals(277, $extra->default);

        // now we edit business1 and change the employee count. new policies should appear
        $business1->additional_employees = 21;
        $business1->save();
        $business1->updatePolicies();
        $policies = $business1->getSortedPolicies(false);

        $this->assertCount(3, $policies);
        $this->assertEquals($this->fixtures['template1']->id, $policies[0]->template_id);
        // now it should have template 3 (> 10 employees) and the policy from template 2 should be deleted
        $this->assertEquals($this->fixtures['template3']->id, $policies[1]->template_id);
        // the 3rd element is the stub again
        $this->assertEquals(277, $policies[2]->template_id);

        // Business 2 - this one was created with > 10 employees
        $business2->updatePolicies();
        $policies = $business2->getSortedPolicies(false);

        $this->assertCount(3, $policies);
        $this->assertEquals($this->fixtures['template1']->id, $policies[0]->template_id);
        // this business gets the one for > 10 employees
        $this->assertEquals($this->fixtures['template3']->id, $policies[1]->template_id);
        // the 3rd element is the stub again
        $this->assertEquals(277, $policies[2]->template_id);
    }

    /**
     * Tests that the policies end up in the correct order when a new one is added.
     * @group policy
     */
    public function testOrder()
    {
        $this->markTestSkipped('must be revisited.');
        $business3 = factory(Business::class)->create(['additional_employees' => 5, 'primary_user_id' => 101]);
        $business3->updatePolicies();

        // initial ordering when business is created
        $policies = $business3->getSortedPolicies(false);

        $this->assertEquals($this->fixtures['template1']->id, $policies[0]->template_id);
        $this->assertEquals(1, $policies[0]->order);
        $this->assertEquals($this->fixtures['template2']->id, $policies[1]->template_id);
        $this->assertEquals(2, $policies[1]->order);
        $this->assertEquals(277, $policies[2]->template_id);  // stub
        // template is in position 4; policy is moved to position 3 by the sorting logic
        $this->assertEquals(4, $policies[2]->order);

        // now we add a new template below the first template in the list
        $this->fixtures['template4'] = PolicyTemplate::create([
            'id' => 4,
            'category_id' => 1,
            'admin_name' => 'Template 4',
            'manual_name' => 'Template 4',
            'effective_date' => date('Y-m-d', time() - 86400),
            'min_employee' => 1,
            'max_employee' => 100,
            'requirement' => ['required'],  // must be array
            'state' => '["ALL"]',
            'status' => 'enabled',
            'order' => 2,
        ]);

        $this->fixtures['template2']->update(['order' => 3]);
        $this->fixtures['template3']->update(['order' => 4]);
        $this->fixtures['template276']->update(['order' => 5]);
        $this->fixtures['template277']->update(['order' => 6]);

        $businessOrderTest1 = factory(Business::class)->create(['additional_employees' => 5, 'primary_user_id' => 202]);
        // update the list again, which should now reorder the policies the business has

        $businessOrderTest1->updatePolicies();
        $policies = $businessOrderTest1->getSortedPolicies(false);

        $this->assertCount(4, $policies);
        $this->assertEquals($this->fixtures['template1']->id, $policies[0]->template_id);
        $this->assertEquals(1, $policies[0]->order);
        $this->assertEquals($this->fixtures['template4']->id, $policies[1]->template_id);
        $this->assertEquals(2, $policies[1]->order);  // moved down
        $this->assertEquals($this->fixtures['template2']->id, $policies[2]->template_id);
        $this->assertEquals(3, $policies[2]->order);  // moved down
        $this->assertEquals(277, $policies[3]->template_id);  // stub
        $this->assertEquals(5, $policies[3]->order);

        // Second Case: Ordering of policies when business employee count changes (but policies don't)
        $business4 = factory(Business::class)->create(['additional_employees' => 5, 'primary_user_id' => 101]);
        $business4->updatePolicies();

        // reorder templates after business is created
        $this->fixtures['template1']->update(['order' => 3]);
        $this->fixtures['template3']->update(['order' => 1]);

        // now update the employee count and verify that the new policy landed in the correct place in the list
        $business4->additional_employees = 21;
        $business4->save();
        $business4->updatePolicies();  // adds template 3 in position 1

        $policies = $business4->getSortedPolicies(false);

        $this->assertCount(4, $policies);
        // now it should have template 3 (> 10 employees) in the first position
        $this->assertEquals($this->fixtures['template3']->id, $policies[0]->template_id);
        $this->assertEquals(1, $policies[0]->order);
        // policy for template 1 doesn't get recreated; it just gets moved down one spot
        $this->assertEquals($this->fixtures['template1']->id, $policies[1]->template_id);
        $this->assertEquals(3, $policies[1]->order);
        // policy for template 4 also gets moved down
        $this->assertEquals($this->fixtures['template4']->id, $policies[2]->template_id);
        $this->assertEquals(3, $policies[2]->order);
        // stub also gets moved down
        $this->assertNull(277, $policies[3]->template_id);  // stub
        $this->assertEquals(5, $policies[3]->order);

        // a new policy is added at the top of the list
        $this->fixtures['template5'] = PolicyTemplate::create([
            'id' => 5,
            'category_id' => 1,
            'admin_name' => 'Template 5',
            'manual_name' => 'Template 5',
            'effective_date' => date('Y-m-d', time() - 86400),
            'min_employee' => 1,
            'max_employee' => 100,
            'requirement' => ['required'],  // must be array
            'state' => '["ALL"]',
            'status' => 'enabled',
            'order' => 0,
        ]);
        $this->fixtures['template1']->update(['order' => 2]);
        $this->fixtures['template4']->update(['order' => 3]);
        $this->fixtures['template2']->update(['order' => 4]);
        $this->fixtures['template3']->update(['order' => 5]);
        $this->fixtures['template276']->update(['order' => 6]);
        $this->fixtures['template277']->update(['order' => 7]);

        $business4->updatePolicies();  // adds the new one
        $policies = $business4->getSortedPolicies(false);

        $this->assertCount(5, $policies);
        $this->assertEquals($this->fixtures['template5']->id, $policies[0]->template_id);
        $this->assertEquals(0, $policies[0]->order);
        // other policies get moved down a spot
        $this->assertEquals($this->fixtures['template3']->id, $policies[1]->template_id);
        $this->assertEquals(2, $policies[1]->order);
        $this->assertEquals($this->fixtures['template1']->id, $policies[2]->template_id);
        $this->assertEquals(3, $policies[2]->order);
        $this->assertEquals($this->fixtures['template4']->id, $policies[3]->template_id);
        $this->assertEquals(4, $policies[3]->order);
        $this->assertEquals(277, $policies[4]->template_id);  // stub
        $this->assertEquals(6, $policies[4]->order);

        // business moves their copy of a policy to the bottom, and a new policy is added just below it
        $policy = Policy::where('business_id', $business4->id)
            ->where('template_id', $this->fixtures['template1']->id)
            ->first();
        $policy->update(['order' => 99]);

        $this->fixtures['template6'] = PolicyTemplate::create([
            'id' => 6,
            'category_id' => 1,
            'admin_name' => 'Template 6',
            'manual_name' => 'Template 6',
            'effective_date' => date('Y-m-d', time() - 86400),
            'min_employee' => 1,
            'max_employee' => 100,
            'requirement' => ['optional'],  // must be array
            'state' => '["ALL"]',
            'status' => 'enabled',
            'order' => 3,
        ]);
        $this->fixtures['template4']->update(['order' => 4]);
        $this->fixtures['template2']->update(['order' => 5]);
        $this->fixtures['template3']->update(['order' => 6]);
        $this->fixtures['template276']->update(['order' => 7]);
        $this->fixtures['template277']->update(['order' => 8]);

        $business4->updatePolicies();  // adds the new one
        $policies = $business4->getSortedPolicies(false);
        $this->assertCount(6, $policies);
        $this->assertEquals($this->fixtures['template5']->id, $policies[0]->template_id);
        $this->assertEquals(0, $policies[0]->order);
        // other policies get moved down a spot
        $this->assertEquals($this->fixtures['template3']->id, $policies[1]->template_id);
        $this->assertEquals(2, $policies[1]->order);
        $this->assertEquals($this->fixtures['template4']->id, $policies[2]->template_id);
        $this->assertEquals(4, $policies[2]->order);
        $this->assertEquals(277, $policies[3]->template_id);  // stub
        $this->assertEquals(6, $policies[3]->order);
        $this->assertEquals($this->fixtures['template1']->id, $policies[4]->template_id);
        $this->assertEquals(99, $policies[4]->order);
        $this->assertEquals($this->fixtures['template6']->id, $policies[5]->template_id);
        $this->assertEquals(100, $policies[5]->order);
    }
}
