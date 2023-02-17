<?php

namespace Tests\Feature;

use App\BusinessPermission;
use App\User;
use Tests\SeededTestCase;

class ManagerPermissionsTest extends SeededTestCase
{
    /**
     * @var BusinessPermission Reference to the Permissions object
     */
    private $permissions;
    /**
     * @var User  A reference to the User object
     */
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->permissions = BusinessPermission::where('business_id', 1)->first();
        $this->manager = User::find(102);
    }

    /**
     * Tests whether managers can access certain pages, and see certain links in the nav bar.
     * Note: this only tests a small sample of pages, not all of them.
     *
     * @return void
     */
    public function testManagerLinks()
    {
        $this->markTestSkipped('must be revisited.');
        // Note: fixture data is stored in the database seeds and is loaded in the setUp() method of the parent class

        // Pro tip: if you get a 500 error from one of the HTTP requests during this tes, you can view the errors by
        // adding `var_dump($response)` just after the line `$response = $this->actingAs($manager)->get('/whatever')`

        $response = $this->actingAs($this->manager)->get('/user');
        $response->assertStatus(200);
        $response->assertSee('HR Director');
        $response->assertSee('Hello, <a href="/user/account"> Manager One</a>');

        // test some nav menu links
        $response->assertSee('<a href="/user"><i class="fa fa-tachometer navigation_img"></i>DASHBOARD</a>');
        $response->assertSee('<a href="/user/job-descriptions"><i class="fa fa-comment-o navigation_img"></i>JOB
                                        DESCRIPTIONS</a>');
        $response->assertSee('<a href="/user/account"><i class="fa fa-user navigation_img"></i>ACCOUNT</a>');

        // make sure we can also visit the pages
        $this->actingAs($this->manager)->get('/user/job-descriptions')->assertStatus(200);
        $this->actingAs($this->manager)->get('/user/account')->assertStatus(200);

        // set some business permissions different from the default (more restrictive)
        $this->permissions->update([
            'm160' => 0,
            'm240' => 0,
            'm260' => 0,
        ]);

        $this->assertFalse($this->manager->permissions('m160'));

        // test the links again
        $response = $this->actingAs($this->manager)->get('/user');
        $response->assertDontSee('<a href="/user"><i class="fa fa-tachometer navigation_img"></i>DASHBOARD</a>');
        $response->assertDontSee('<a href="/user/job-descriptions"><i class="fa fa-comment-o navigation_img"></i>JOB
                                        DESCRIPTIONS</a>');
        $response->assertDontSee('<a href="/user/account"><i class="fa fa-user navigation_img"></i>ACCOUNT</a>');

        // we should no longer be able to visit the pages
        $this->actingAs($this->manager)->get('/user/job-descriptions')->assertRedirect('/user');
        $this->actingAs($this->manager)->get('/user/account')->assertRedirect('/user');
    }

    /**
     * Tests whether managers can see the buttons to approve/reject time off requests.
     *
     * @return void
     */
    public function testTimeOffApprovalLinks()
    {
        $this->markTestSkipped('must be revisited.');
        // Note: white space is a problem when trying to find complex HTML with `assertSee()`, so we're using
        // `assertSeeInOrder()` here to test for the various HTML tags we're looking for. This lets us use
        // shorter strings so we don't have to worry about white space as much

        // manager who can approve time off requests - should see approve/deny buttons
        $this->actingAs($this->manager)->get('/user')
            ->assertSeeInOrder([
                '<form method="POST" action="http://localhost/user/employees/timeoff/1"',
                '<button type="submit"',
                'class="btn btn-default btn-xs form-btn"',
                'value="approve" name="action">',
                '<i class="fa fa-check icon_green"></i>',
                '<button type="submit"',
                'class="btn btn-default btn-xs form-btn"',
                'value="deny" name="action">',
                '<i class="fa fa-times icon_red"></i>',
            ]);
        // manager who can't approve time off requests - should still see them on the dashboard
        // but the approve/deny buttons should be gone
        $this->permissions->update([
            'm144' => 0,  // manager approve/deny time off
        ]);
        $this->actingAs($this->manager)->get('/user')
            ->assertDontSee('<form method="POST" action="http://localhost/user/employees/timeoff/1"')
            ->assertDontSee('value="approve" name="action"')
            ->assertDontSee('<i class="fa fa-check icon_green"></i>')
            ->assertDontSee('value="deny" name="action"')
            ->assertDontSee('<i class="fa fa-times icon_red"></i>');
    }

    /**
     * Tests more restrictive permissions like "no dashboard access" and "no hr director access".
     *
     * @return void
     */
    public function testManagerNoAccessLinks()
    {
        $this->markTestSkipped('must be revisited.');
        // manager access to everything but the dashboard - should still be able to see other pages (BENT-542)
        $this->permissions->update([
            'm160' => 1,
            'm240' => 0,
            'm260' => 1,
        ]);

        $this->actingAs($this->manager)->get('/user')
            ->assertStatus(200)
            ->assertDontSee('<h3>Anniversaries</h3>')
            ->assertSee('Please choose an option from the menu above.');
        $this->actingAs($this->manager)->get('/user/job-descriptions')
            ->assertStatus(200)
            ->assertSee('<h3>Job Descriptions</h3>');
        $this->actingAs($this->manager)->get('/user/forms')
            ->assertStatus(200)
            ->assertSee('<h3>Forms List</h3>');

        // block manager access to the whole app - should not be able to use any other pages
        $this->permissions->update([
            'm100' => 0,
        ]);
        $this->actingAs($this->manager)->get('/user')
            ->assertStatus(200)
            ->assertDontSee('<h3>Anniversaries</h3>')
            ->assertSee('You Do Not Have Access to HR Director');
        // other pages should now redirect to the dashboard
        // (see `handle()` method in laravel/app/Http/Middleware/Permissions.php)
        $this->actingAs($this->manager)->get('/user/job-descriptions')->assertRedirect('/user');
        $this->actingAs($this->manager)->get('/user/account')->assertRedirect('/user');
    }
}
