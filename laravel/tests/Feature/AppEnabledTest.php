<?php

namespace Tests\Feature;

use App\Business;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Tests\SeededTestCase;

/**
 * Class AppEnabledTest.
 *
 * Tests the logic for HR Director and BonusPro being enabled or disabled
 */
class AppEnabledTest extends SeededTestCase
{
    const BP_NO_ACCESS = '<div class="alert alert-danger">You don&#039;t have access to BonusPro. Please contact Customer Service.</div>';
    const BP_EXPIRED = '<div class="alert alert-danger">Your BonusPro subscription is expired. Please contact Customer Service.</div>';

    /**
     * Tests the logic for HR Director and BonusPro being enabled or disabled.
     *
     * @return void
     */
    public function testAppsEnabled()
    {
        $this->markTestSkipped('must be revisited.');
        // Note: Whatever you pass into actingAs() gets injected directly into the Auth::user() object. This means that
        // we have to requery the owner's "business" relation whenever saving the business, or we'll end up with stale
        // data in the app.

        $owner = User::find(101);
        $business = Business::find(1);

        // Case 1: HR enabled, BP enabled, exp date in the future
        $response = $this->actingAs($owner)->get('/user');
        $response->assertStatus(200);
        $response->assertDontSee('You Do Not Have Access to HR Director');
        $response = $this->actingAs($owner)->get('/bonuspro');
        $response->assertDontSee(self::BP_NO_ACCESS);
        $response->assertDontSee(self::BP_EXPIRED);

        // Case 2: BP expiration date in past
        $business->update(['bonuspro_expiration_date' => new Carbon('2010-01-01 00:00:00'), 'name' => 'Name EDITED']);
        $owner->load('business');  // refresh related model
        $response = $this->actingAs($owner)->get('/bonuspro');
        $response->assertRedirect('/user');
        $response->assertSessionHas(['bonuspro_enabled' => false]);
        $response = $this->actingAs($owner)->get('/user');  // need to follow the redirect manually to check error message display
        $response->assertDontSee(self::BP_NO_ACCESS);
        $response->assertSee(self::BP_EXPIRED);

        // Case 3: BP expiration date in past, but has lifetime access
        $business->update(['bonuspro_lifetime_access' => 1]);
        $owner->load('business');  // refresh related model
        $response = $this->actingAs($owner)->get('/bonuspro');
        $response->assertStatus(200);
        $response->assertSessionHas(['bonuspro_enabled' => true]);
        $response->assertDontSee(self::BP_NO_ACCESS);
        $response->assertDontSee(self::BP_EXPIRED);

        // Case 3: BP expiration date null, but has lifetime access
        $business->update(['bonuspro_expiration_date' => null]);
        $owner->load('business');  // refresh related model
        $response = $this->actingAs($owner)->get('/bonuspro');
        $response->assertSessionHas(['bonuspro_enabled' => true]);
        $response->assertDontSee(self::BP_NO_ACCESS);
        $response->assertDontSee(self::BP_EXPIRED);

        // Case 4: BP disabled
        $business->update(['bonuspro_enabled' => 0]);
        $owner->load('business');  // refresh related model
        $response = $this->actingAs($owner)->get('/bonuspro');
        $response->assertRedirect('/user');
        $response->assertSessionHas(['bonuspro_enabled' => false]);
        $response = $this->actingAs($owner)->get('/user');  // need to follow the redirect manually to check error message display
        $response->assertSee(self::BP_NO_ACCESS);
        $response->assertDontSee(self::BP_EXPIRED);

        // Case 5: HR Director disabled
        $business->update(['bonuspro_enabled' => 1, 'hrdirector_enabled' => 0]);
        $owner->load('business');  // refresh related model
        $response = $this->actingAs(User::find(101))->get('/user');
        $response->assertStatus(200);
        $response->assertSee('You Do Not Have Access to HR Director');
    }
}
