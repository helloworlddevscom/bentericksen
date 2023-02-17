<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\SeededTestCase;

/**
 * Class PaymentCardTest
 * @package Tests\Unit
 */
class PaymentCardTest extends SeededTestCase
{

    /**
     * @vars
     * @return void
     * @group payment
     */
    private $card = "card_1HRHrz2eZvKYlo2C4YiE0ydW";
    private $source = ['data' => ['type' => 'card']];
    private $cardData = ['data' => ['exp_month' => '10', 'exp_year' => '2023']];
    private $stripe_id = 'cus_I1pCulAP3Lfcbs';
    private static $user;

    /**
     * Test that a user can create a payment card
     * @return void
     * @group payment
     */
    public function testCanCreatePaymentInstrument()
    {

        $this->withoutExceptionHandling();

        self::$user = User::find(102);
        self::$user->stripe_id = $this->stripe_id;

        $this->actingAs(self::$user)
            ->post('/payment/cards/', $this->source)
            ->assertStatus(200);

        $this->assertDatabaseHas('stripe_cards', [
            'id'      => $this->card,
            'user_id' => self::$user->id
        ]);

    }

    /**
     * Test that a user can retrieve a payment card
     * @return void
     * @group payment
     */
    public function testCanGetPaymentInstrument()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(self::$user)
            ->get('/payment/cards/' . $this->card)
            ->assertStatus(200);
    }

    /**
     * Test that a user can retrieve all payment cards
     * @return void
     * @group payment
     */
    public function testCanGetAllPaymentInstruments()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(self::$user)
            ->get('/payment/cards/')
            ->assertStatus(200);
    }

}
