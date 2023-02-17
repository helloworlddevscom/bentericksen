<?php


namespace Bentericksen\Payment\Clients;

use Stripe\StripeClient as Client;

/**
 * Class StripeClient
 * @package Bentericksen\Payment\Client
 */
class StripeClient
{

    /**
     * @return Client|FakeClient
     */
    public static function getInstance() {

        // Return FakeClient if this is a test
        if(config('app.env') === "testing") {
            return new FakeClient();
        }

        // Return StripeClient for sandbox
        if(config('app.env') !== "production") {
            return self::sandbox();
        }

        // Return StripeClient for production
        return self::production();

    }

    /**
     * @return Client
     */
    public static function sandbox() {
        return new Client(config('stripe.api.sec_key'));
    }

    /**
     * @return Client
     */
    public static function production() {
        return new Client(config('stripe.api.prod.sec_key'));
    }

}

/**
 * Class FakeClient
 * @package Bentericksen\Payment\Clients
 */
class FakeClient
{

    public $customers;
    public $products;
    public $subscriptions;

    public function __construct() {

        $this->customers = new FakeCustomerService();
        $this->products = new FakeProductService();
        $this->subscriptions = new FakeSubscriptionService();

    }

}

/**
 * Class FakeCustomerService
 * @package Bentericksen\Payment\Clients
 */
class FakeCustomerService
{

    private $createCustomerObject = '{"id": "cus_I1itgRYyA5In8c", "object": "customer", "address": null, "balance": 0, "created": 1600181284, "currency": "usd", "default_source": null, "delinquent": false, "description": "My First Test Customer (created for API docs)", "discount": null, "email": null, "invoice_prefix": "9CBFD91", "invoice_settings": { "custom_fields": null, "default_payment_method": null, "footer": null }, "livemode": false, "metadata": {}, "name": null, "next_invoice_sequence": 1, "phone": null, "preferred_locales": [], "shipping": null, "tax_exempt": "none"}';
    private $updateCustomerObject = '{"id": "cus_I1itgRYyA5In8c", "object": "customer", "address": null, "balance": 0, "created": 1600181284, "currency": "usd", "default_source": null, "delinquent": false, "description": null, "discount": null, "email": "unit@test.com", "invoice_prefix": "9CBFD91", "invoice_settings": { "custom_fields": null, "default_payment_method": null, "footer": null }, "livemode": false, "metadata": { "order_id": "6735" }, "name": null, "next_invoice_sequence": 1, "phone": null, "preferred_locales": [], "shipping": null, "tax_exempt": "none"}';
    private $deleteCustomerObject = '{"id": "cus_I1itgRYyA5In8c", "object": "customer", "deleted": true}';
    private $createCardObject = '{"id": "card_1HRHrz2eZvKYlo2C4YiE0ydW", "object": "card", "address_city": null, "address_country": null, "address_line1": null, "address_line1_check": null, "address_line2": null, "address_state": null, "address_zip": null, "address_zip_check": null, "brand": "Visa", "country": "US", "customer": "cus_I1KH7VU54CWKCg", "cvc_check": "pass", "dynamic_last4": null, "exp_month": 8, "exp_year": 2021, "fingerprint": "Xt5EWLLDS7FJjR1c", "funding": "credit", "last4": "4242", "metadata": {}, "name": null, "tokenization_method": null }';
    private $updateCardObject = '{"id": "card_1HRHrz2eZvKYlo2C4YiE0ydW", "object": "card", "address_city": null, "address_country": null, "address_line1": null, "address_line1_check": null, "address_line2": null, "address_state": null, "address_zip": null, "address_zip_check": null, "brand": "Visa", "country": "US", "customer": "cus_I1KH7VU54CWKCg", "cvc_check": "pass", "dynamic_last4": null, "exp_month": 10, "exp_year": 2023, "fingerprint": "Xt5EWLLDS7FJjR1c", "funding": "credit", "last4": "4242", "metadata": {}, "name": null, "tokenization_method": null }';
    private $deleteCardObject = '{"id": "card_1HRHrz2eZvKYlo2C4YiE0ydW", "object": "card", "deleted": true }';
    private $createAccountObject = '{"id": "ba_1HRfwv2eZvKYlo2CakbWcvia", "object": "bank_account", "account_holder_name": "Jane Austen", "account_holder_type": "individual", "bank_name": "STRIPE TEST BANK", "country": "US", "currency": "usd", "customer": "cus_I1jKfNxpJv7NPE", "fingerprint": "1JWtPxqbdX5Gamtz", "last4": "6789", "metadata": {}, "routing_number": "110000000", "status": "new" }';
    private $updateAccountObject = '{"id": "ba_1HRfwv2eZvKYlo2CakbWcvia", "object": "bank_account", "account_holder_name": "Unit Test", "account_holder_type": "individual", "bank_name": "STRIPE TEST BANK", "country": "US", "currency": "usd", "customer": "cus_I1jKfNxpJv7NPE", "fingerprint": "1JWtPxqbdX5Gamtz", "last4": "6789", "metadata": {}, "routing_number": "110000000", "status": "new" }';
    private $deleteAccountObject = '{"id": "ba_1HRfwv2eZvKYlo2CakbWcvia", "object": "bank_account", "deleted": true}';
    private $verifyAccountObj = '{"id": "ba_1HRfwv2eZvKYlo2CakbWcvia", "object": "bank_account", "account_holder_name": "Jane Austen", "account_holder_type": "individual", "bank_name": "STRIPE TEST BANK", "country": "US", "currency": "usd", "customer": "cus_I1jKfNxpJv7NPE", "fingerprint": "1JWtPxqbdX5Gamtz", "last4": "6789", "metadata": {}, "routing_number": "110000000", "status": "new", "name": "Jenny Rosen"}';

    public function create($customerData) {

        if(isset($customerData)) {

            return json_decode($this->createCustomerObject,true);

        }

        return false;

    }

    public function update($customer, $customerData) {

        if(isset($customer, $customerData)) {

            return json_decode($this->updateCustomerObject,true);

        }

        return false;

    }

    public function delete($customer) {

        if(isset($customer)) {

            return json_decode($this->deleteCustomerObject,true);

        }

        return false;

    }

    public function createSource($customer, $instrumentData) {

        if(isset($instrumentData)) {

            $resultObj = $instrumentData['type'] === "card" ? $this->createCardObject : $this->createAccountObject;

            return json_decode($resultObj, true);

        }

        return false;

    }

    public function updateSource($customer, $instrument, $instrumentData) {

        if(isset($instrument, $instrumentData)) {

            $resultObj = strpos($instrument, "card_") !== false ? $this->updateCardObject : $this->updateAccountObject;

            return json_decode($resultObj, true);

        }

        return false;

    }

    public function deleteSource($customer, $instrument) {

        if(isset($instrument)) {

            $resultObj = strpos($instrument, "card_") !== false ? $this->deleteCardObject : $this->deleteAccountObject;

            return json_decode($resultObj, true);

        }

        return false;

    }

    public function verifySource($customer, $account, $amounts) {

        if(isset($account, $amounts)) {

            return json_decode($this->verifyAccountObj, true);

        }

        return false;

    }

}

/**
 * Class FakeProductService
 * @package Bentericksen\Payment\Clients
 */
class FakeProductService
{

    private $createProductObj = '{"id": "prod_I1ihxxmHv0p51K", "object": "product", "active": true, "attributes": [], "created": 1600180532, "description": null, "images": [], "livemode": false, "metadata": {}, "name": "Gold Special", "statement_descriptor": null, "type": "service", "unit_label": null, "updated": 1600180532}';
    private $updateProductObj = '{"id": "prod_I1ihxxmHv0p51K", "object": "product", "active": true, "attributes": [], "created": 1600180532, "description": null, "images": [], "livemode": false, "metadata": {}, "name": "Unit Test", "statement_descriptor": null, "type": "service", "unit_label": null, "updated": 1600180532}';
    private $deleteProductObj = '{"id": "prod_I1ihxxmHv0p51K", "object": "product", "deleted": true}';

    public function create($productData) {

        if(isset($productData)) {

            return json_decode($this->createProductObj, true);
        }

        return false;

    }

    public function update($product, $metaData)
    {

        if(isset($product, $metaData)) {

            return json_decode($this->updateProductObj, true);

        }

        return false;

    }

    public function delete($product) {

        if(isset($product)) {

            return json_decode($this->deleteProductObj, true);

        }

        return false;

    }

}

class FakeSubscriptionService
{

    private $createSubscriptionObj = '{"id": "sub_36VrPHS2vVxJMq", "object": "subscription", "application_fee_percent": null, "billing_cycle_anchor": 1387222772, "billing_thresholds": null, "cancel_at": null, "cancel_at_period_end": false, "canceled_at": 1445989053, "collection_method": "charge_automatically", "created": 1386790772, "current_period_end": 1447702772, "current_period_start": 1444678772, "customer": "cus_I6knGHKTXGOGDt", "days_until_due": null, "default_payment_method": null, "default_source": null, "default_tax_rates": [], "discount": null, "ended_at": 1445989053, "items": { "object": "list", "data": [ { "id": "si_18SfBn2eZvKYlo2C1fwOImYF", "object": "subscription_item", "billing_thresholds": null, "created": 1386790772, "metadata": {}, "price": { "id": "40", "object": "price", "active": true, "billing_scheme": "per_unit", "created": 1386694689, "currency": "usd", "livemode": false, "lookup_key": null, "metadata": { "charset": "utf-8", "content": "40" }, "nickname": null, "product": "prod_BTcfj5EqyqxDVn", "recurring": { "aggregate_usage": null, "interval": "week", "interval_count": 5, "usage_type": "licensed" }, "tiers_mode": null, "transform_quantity": null, "type": "recurring", "unit_amount": 5465, "unit_amount_decimal": "5465" }, "quantity": 1, "subscription": "sub_36VrPHS2vVxJMq", "tax_rates": [] } ], "has_more": false, "url": "/v1/subscription_items?subscription=sub_36VrPHS2vVxJMq" }, "latest_invoice": null, "livemode": false, "metadata": {}, "next_pending_invoice_item_invoice": null, "pause_collection": null, "pending_invoice_item_interval": null, "pending_setup_intent": null, "pending_update": null, "schedule": null, "start_date": 1386790772, "status": "active", "transfer_data": null, "trial_end": 1387222772, "trial_start": 1386790772}';
    private $updateSubscriptionObj = '{"id": "sub_36VrPHS2vVxJMq", "object": "subscription", "application_fee_percent": null, "billing_cycle_anchor": 1387222772, "billing_thresholds": null, "cancel_at": null, "cancel_at_period_end": false, "canceled_at": 1445989053, "collection_method": "charge_automatically", "created": 1386790772, "current_period_end": 1447702772, "current_period_start": 1444678772, "customer": "cus_I6knGHKTXGOGDt", "days_until_due": null, "default_payment_method": null, "default_source": null, "default_tax_rates": [], "discount": null, "ended_at": 1445989053, "items": { "object": "list", "data": [ { "id": "si_18SfBn2eZvKYlo2C1fwOImYF", "object": "subscription_item", "billing_thresholds": null, "created": 1386790772, "metadata": {}, "price": { "id": "40", "object": "price", "active": true, "billing_scheme": "per_unit", "created": 1386694689, "currency": "usd", "livemode": false, "lookup_key": null, "metadata": { "charset": "utf-8", "content": "40" }, "nickname": null, "product": "prod_BTcfj5EqyqxDVn", "recurring": { "aggregate_usage": null, "interval": "week", "interval_count": 5, "usage_type": "licensed" }, "tiers_mode": null, "transform_quantity": null, "type": "recurring", "unit_amount": 5465, "unit_amount_decimal": "5465" }, "quantity": 1, "subscription": "sub_36VrPHS2vVxJMq", "tax_rates": [] } ], "has_more": false, "url": "/v1/subscription_items?subscription=sub_36VrPHS2vVxJMq" }, "latest_invoice": null, "livemode": false, "metadata": {}, "next_pending_invoice_item_invoice": null, "pause_collection": null, "pending_invoice_item_interval": null, "pending_setup_intent": null, "pending_update": null, "schedule": null, "start_date": 1386790772, "status": "incomplete", "transfer_data": null, "trial_end": 1387222772, "trial_start": 1386790772}';
    private $cancelSubscriptionObj = '{"id": "sub_36VrPHS2vVxJMq", "object": "subscription", "application_fee_percent": null, "billing_cycle_anchor": 1387222772, "billing_thresholds": null, "cancel_at": null, "cancel_at_period_end": false, "canceled_at": 1601346828, "collection_method": "charge_automatically", "created": 1386790772, "current_period_end": 1447702772, "current_period_start": 1444678772, "customer": "cus_I6knGHKTXGOGDt", "days_until_due": null, "default_payment_method": null, "default_source": null, "default_tax_rates": [], "discount": null, "ended_at": 1445989053, "items": { "object": "list", "data": [ { "id": "si_18SfBn2eZvKYlo2C1fwOImYF", "object": "subscription_item", "billing_thresholds": null, "created": 1386790772, "metadata": {}, "price": { "id": "40", "object": "price", "active": true, "billing_scheme": "per_unit", "created": 1386694689, "currency": "usd", "livemode": false, "lookup_key": null, "metadata": { "charset": "utf-8", "content": "40" }, "nickname": null, "product": "prod_BTcfj5EqyqxDVn", "recurring": { "aggregate_usage": null, "interval": "week", "interval_count": 5, "usage_type": "licensed" }, "tiers_mode": null, "transform_quantity": null, "type": "recurring", "unit_amount": 5465, "unit_amount_decimal": "5465" }, "quantity": 1, "subscription": "sub_36VrPHS2vVxJMq", "tax_rates": [] } ], "has_more": false, "url": "/v1/subscription_items?subscription=sub_36VrPHS2vVxJMq" }, "latest_invoice": null, "livemode": false, "metadata": {}, "next_pending_invoice_item_invoice": null, "pause_collection": null, "pending_invoice_item_interval": null, "pending_setup_intent": null, "pending_update": null, "schedule": null, "start_date": 1386790772, "status": "canceled", "transfer_data": null, "trial_end": 1387222772, "trial_start": 1386790772 ';

    public function create($subscriptionData) {

        if(isset($subscriptionData)) {

            return json_decode($this->createSubscriptionObj, true);
        }

        return false;

    }

    public function update($subscription, $subscriptionData)
    {

        if(isset($subscription, $subscriptionData)) {

            return json_decode($this->updateSubscriptionObj, true);

        }

        return false;

    }

    public function cancel($subscription) {

        if(isset($subscription)) {

            return json_decode($this->cancelSubscriptionObj, true);

        }

        return false;
    }
}
