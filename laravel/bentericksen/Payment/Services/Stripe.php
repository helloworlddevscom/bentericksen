<?php

namespace Bentericksen\Payment\Services;

use Bentericksen\Payment\Payment\PaymentService;
use Bentericksen\Payment\Products\StripeProduct;
use Bentericksen\Payment\Instruments\Cards\StripeCard;
use Bentericksen\Payment\Instruments\BankAccounts\StripeBankAccount;
use Bentericksen\Payment\Subscriptions\StripeSubscription;
use Bentericksen\Payment\Customers\StripeCustomer;

/**
 * Class Stripe
 * Stripe payment interface implementation
 * @package Bentericksen\Payment\Services
 */
class Stripe
{

    /**
     * @return StripeProduct
     */
    public function productComponent() {
        return new StripeProduct();
    }

    /**
     * @return StripeCard
     */
    public function cardComponent() {
        return new StripeCard();
    }

    /**
    * @return StripeBankAccount
    */
    public function bankAccountComponent() {
        return new StripeBankAccount();
    }

    /**
     * @return StripeSubscription
     */
    public function subscriptionComponent() {
        return new StripeSubscription();
    }

    /**
     * @return StripeCustomer
     */
    public function customerComponent() {
        return new StripeCustomer();
    }

}
