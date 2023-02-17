<?php

namespace Tests\Unit;

use App\User;
use Tests\SeededTestCase;

/**
 * Class SubscriptionTest
 * @package Tests\Unit
 */
class PaymentSubscriptionTest extends SeededTestCase
{

    /**
     * @vars
     * @group payment
     */
    private $subscription  = 'sub_36VrPHS2vVxJMq';
    private $subscriptionData = ['data' => ['customer' => 'cus_I6knGHKTXGOGDt', 'items' => [['price' => 'price_1HUYr32eZvKYlo2CLmELVYP5']]]];
    private $stripe_id = 'cus_I6knGHKTXGOGDt';
    private static $user;

    /**
     * @group payment
     */
    public function testCanCreateSubscription()
    {

        $this->withoutExceptionHandling();

        self::$user = User::find(102);
        self::$user->stripe_id = $this->stripe_id;

        $this->actingAs(self::$user)
            ->post('/payment/subscriptions/', $this->subscriptionData)
            ->assertStatus(200);

        $this->assertDatabaseHas('stripe_subscriptions', [
            'id'      => $this->subscription
        ]);

    }

    /**
     * Test that a user can retrieve a subscription
     * @return void
     * @group payment
     */
    public function testCanGetSubscription()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(self::$user)
            ->get('/payment/subscriptions/' . $this->subscription)
            ->assertStatus(200);
    }

    /**
     * Test that a user can retrieve all subscriptions
     * @return void
     * @group payment
     */
    public function testCanGetAllSubscriptions()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(self::$user)
            ->get('/payment/subscriptions/')
            ->assertStatus(200);
    }

}
