<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Class PaymentCustomerTest
 * @package Tests\Unit
 */
class PaymentCustomerTest extends TestCase
{

    /**
     * @vars
     * @group payment
     */
    private $customer = "cus_I1NCbkFPOuWbcw";
    private $customerData = ['data' => ['description' => 'This is a test customer', 'name' => 'Unit Test']];
    private $updateData = ['data' => ['email' => 'unit@test.com']];
    private static $user;

    /**
     * Test that a user can create a customer
     * @return void
     * @group payment
     */
    public function testCanCreateCustomer()
    {

        $this->withoutExceptionHandling();

        self::$user = factory(User::class)->create();

        $this->actingAs(self::$user)
            ->post('/payment/customers/', $this->customerData)
            ->assertStatus(200);

        $this->assertDatabaseHas('stripe_customers', [
            'user_id' => self::$user->id
        ]);

    }

    /**
     * Test that a user can retrieve a customer
     * @return void
     * @group payment
     */
    public function testCanGetCustomer()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(self::$user)
            ->get('/payment/customers/' . $this->customer)
            ->assertStatus(200);
    }

    /**
     * Test that a user can retrieve all customers
     * @return void
     * @group payment
     */
    public function testCanGetAllCustomers()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(self::$user)
            ->get('/payment/customers/')
            ->assertStatus(200);
    }

}
