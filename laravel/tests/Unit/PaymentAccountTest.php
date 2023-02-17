<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\SeededTestCase;

/**
 * Class PaymentAccountTest
 * @package Tests\Unit
 */
class PaymentAccountTest extends SeededTestCase
{

    /**
     * @vars
     * @group payment
     */
    private $account          = "ba_1HRfwv2eZvKYlo2CakbWcvia";
    private $source           = ['data' => ['type' => 'account']];
    private $accountData      = ['data' => ['account_holder_name' => 'Unit Test']];
    private $amounts          = ['data' => ['amounts' => [32, 45]]];
    private $stripe_id        = 'cus_I1pCulAP3Lfcbs';
    private static $user;

    /**
     * Test that a user can create a payment account
     * @return void
     * @group payment
     */
    public function testCanCreatePaymentInstrument()
    {

        $this->withoutExceptionHandling();

        self::$user = User::find(102);
        self::$user->stripe_id = $this->stripe_id;

        $this->actingAs(self::$user)
            ->post('/payment/accounts/', $this->source)
            ->assertStatus(200);

        $this->assertDatabaseHas('stripe_bank_accounts', [
            'id' => $this->account,
            'user_id' => self::$user->id
        ]);

    }

    /**
     * Test that a user can retrieve a payment account
     * @return void
     * @group payment
     */
    public function testCanGetPaymentInstrument()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(self::$user)
            ->get('/payment/accounts/' . $this->account)
            ->assertStatus(200);
    }

    /**
     * Test that a user can retrieve all payment accounts
     * @return void
     * @group payment
     */
    public function testCanGetAllPaymentInstruments()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(self::$user)
            ->get('/payment/accounts/')
            ->assertStatus(200);
    }

    /**
     * Test that a user can delete a payment account
     * @return void
     * @group payment
     */
    public function testVerifyBankAccount()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(self::$user)
            ->post('/payment/accounts/' . $this->account, $this->amounts)
            ->assertStatus(200);

    }

}
