<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

/**
 * Class PaymentProductTest
 * @package Tests\Unit
 */
class PaymentProductTest extends TestCase
{

    /**
     * @vars
     * @group payment
     */
    private $product_id          = 'prod_I2zlvc7dBmZYjz';
    private static $user;

    /**
     * @group payment
     */
    protected function setUp(): void
    {
        parent::setUp();
        self::$user = factory(User::class)->create();
    }

    /**
     * Test that a user can retrieve a product
     * @return void
     * @group payment
     */
    public function testCanGetProduct()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(self::$user)
            ->get('/payment/products/' . $this->product_id)
            ->assertStatus(200);
    }

    /**
     * Test that a user can retrieve all products
     * @return void
     * @group payment
     */
    public function testCanGetAllProducts()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(self::$user)
            ->get('/payment/products/')
            ->assertStatus(200);
    }

}
