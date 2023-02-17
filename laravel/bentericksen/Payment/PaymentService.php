<?php

namespace Bentericksen\Payment;

use App\User;
use Bentericksen\Payment\Instruments\Cards\StripeCard;
use \Illuminate\Database\QueryException;
use Bentericksen\Payment\Clients\StripeClient;
use Bentericksen\Payment\Prices\Models\StripePrice;
use App\Facades\PaymentService as PayService;
use Bentericksen\Payment\Subscriptions\Models\StripeSubscription;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Business;
use App\BusinessAsas;

/**
 * Class Payment
 * @package Bentericksen\Payment\Payment
 */
class PaymentService
{

    const ONE_TIME_FEE_ID = 'price_1KkFZHBrzeFxdcPR8ScAAF4e';

    const ACH_VERIFICATION_SUCCESS_MESSAGE = "Account information submitted. For verification purposes, two small deposits have been made " .
    "to your account. They will take 1-2 business days to appear on your statement with a description that " .
    "includes AMTS. Once received, please return to this page and enter the amounts to complete account verification.";

    /**
     * Get BE products/plans
     * @param $customer
     * @return array
     */
    public static function getPlans() {

        $plans = [];

        // Get all products
        $products = PayService::productComponent()->getAllProducts();

        // Let's attach a plan to each product.  A plan represents  a Stripe Price (https://stripe.com/docs/api/prices).
        if(PaymentHelper::isValid($products)) {

            $_products = $products['data'];

            foreach($_products as $product) {

                $plans[$product['name']] = [];

                foreach($product->prices as $price) {

                    array_push(
                        $plans[$product['name']],
                        ['price_id' => $price->price_id,
                            'id' => $price->id,
                            'description' => $price->description,
                            'price' => number_format((float)($price->unit_amount/100), 2, '.', '')
                        ]
                    );

                }

            }

        } else {

            // Handle error
            return PaymentHelper::handleEvent(
                'Error retrieving product list.', PaymentHelper::loginfo(), 'payment_error'
            );

        }

        // Handle success
        return PaymentHelper::handleEvent($plans, null, 'payment_event');

    }

    /**
     * Get's the customer's discount
     * @param $user
     * @return array
     */
    public static function getCoupon($user): array
    {

        $stripeCustomer = PayService::customerComponent()->getCustomer($user);

        if(!PaymentHelper::isValid($stripeCustomer)) {
            PaymentHelper::handleEvent('Stripe Customer doesn\'t exist', PaymentHelper::loginfo(), 'payment_error', true, false);
            return ['success' => true, 'data' => null];
        }

        // Grab the customer's discount
        $coupon = PaymentHelper::objToArray(PayService::customerComponent()->getCustomer($user)['data']['discount']);

        // Handle success
        return PaymentHelper::handleEvent($coupon, null, 'payment_event');

    }

    /**
     * @param $access
     * @param $user
     * @return array
     */
    public static function getDefaultCardInfo($user): array
    {
        $sortedCards = self::getSortedCustomerCards($user);
        if(count($sortedCards)) {
            return [
                "last4" => $sortedCards[0]->last4,
                "brand" => $sortedCards[0]->brand
            ];
        }
        return [];
    }

    /**
     * Returns an array of the Business' cards with a default source as the first array element
     * if a default card source exists.
     * @param $access
     * @param $user
     * @return mixed
     */
    public static function getSortedCustomerCards($user) {

        $cards = PayService::cardComponent()->getAllPaymentInstruments($user)['data'];
        return self::sortCustomerInstruments($cards, $user);

    }

    /**
     *
     * Returns an array of the Business' accounts with a default source as the first array element
     * if a default account source exists.
     * @param $access
     * @param $user
     * @return mixed
     */
    public static function getSortedCustomerAccounts($user) {

        $accounts = PayService::bankAccountComponent()->getAllPaymentInstruments($user)['data'];
        return self::sortCustomerInstruments($accounts, $user);

    }

    /**
     * @param $instruments
     * @param $user
     * @return mixed
     */
    private static function sortCustomerInstruments($instruments, $user) {

        $sortedInstruments = [];
        if(count($instruments)) {
            $customer = PayService::customerComponent()->getCustomer($user);
            if(!$customer['success']) {
                return [];
            }
            for($i = 0; $i < sizeOf($instruments); $i++) {
                if($instruments[$i]->id === $customer['data']->default_source) {
                    array_unshift($sortedInstruments, $instruments[$i]);
                } else {
                    $sortedInstruments[$i] = $instruments[$i];
                }
            }
        }

        return $sortedInstruments;
    }

    /**
     * Add payment instrument
     * @param $user
     * @param $instrument_token
     * @param $default_instrument
     * @param $response
     * @param $card
     * @param false $internal
     * @return array
     */
    // Need to refactor PaymentHelper::handleEvent() responses to return both descriptive messaging and Stripe objects.
    // Until then this method will use an $internal flag to determine what to return.
    public static function addInstrument($user, $instrument_token, $default_instrument, $response, $card, $internal = false)
    {

        // Make sure we have a Stripe Customer on the User
        $customer = self::getStripeCustomer($user, $response);

        if (!PaymentHelper::isValid($customer)) {
            // Handle error
            return PaymentHelper::handleEvent(
                'Missing StripeCustomer instance', PaymentHelper::loginfo(), 'payment_error'
            );
        }

        if(!$card) {
            // Create StripeBankAccount
            $instrument = PayService::bankAccountComponent()->createPaymentInstrument($user, ['source' => $instrument_token], $response);
        } else {
            // Create StripeCard
            $instrument = PayService::cardComponent()->createPaymentInstrument($user, ['source' => $instrument_token], $response);
        }

        // Handle setting the customer's default source
        if($default_instrument === "true") {
            self::handleDefaultSourceUpdate($user, $instrument, $response);
        }

        if(PaymentHelper::isValid($instrument) && $instrument['data']['object'] === "bank_account") {
            // Handle success
            return PaymentHelper::handleEvent(self::ACH_VERIFICATION_SUCCESS_MESSAGE, null, 'payment_event');
        }

        if(PaymentHelper::isValid($instrument) && $instrument['data']['object'] === "card") {
            // Handle success
            return $internal === true ? $instrument :
                PaymentHelper::handleEvent(
                    'Instrument successfully created', null, 'payment_event', false, false
                );
        }

    }

    /**
     * Update payment instrument
     * @param $user
     * @param $instrument
     * @param $instrument_data
     * @param $default_instrument
     * @param $response
     * @return array
     */
    // Handles credit cards only at this point
    public static function updateInstrument($user, $instrument, $instrument_data, $default_instrument, $response)
    {

        // assume the update fails
        $updated = false;

        // Make sure we have a Stripe Customer on the User
        $customer = self::getStripeCustomer($user, $response);

        if (!PaymentHelper::isValid($customer)) {
            // Handle error
            return PaymentHelper::handleEvent(
                'Missing StripeCustomer instance', PaymentHelper::loginfo(), 'payment_error'
            );
        }

        // Update the payment instrument
        if(!is_null($instrument_data['name'] ) && !is_null($instrument_data['exp_month'] && !is_null($instrument_data['exp_year']))) {
            $instrument = PayService::cardComponent()->updatePaymentInstrument($user, $instrument, $instrument_data, $response);
            $updated = PaymentHelper::isValid($instrument);
        }

        // Handle setting the customer's default source
        if($default_instrument === "true") {
            $defaultUpdated = self::handleDefaultSourceUpdate($user, $instrument, $response);
            $updated = PaymentHelper::isValid($defaultUpdated);
        }

        if($updated) {
            // Handle success
            return PaymentHelper::handleEvent(
                'Payment instrument successfully updated.', PaymentHelper::loginfo(), 'payment_event', true, false
            );
        }

        // Handle error
        return PaymentHelper::handleEvent(
            'Payment instrument update failed.', PaymentHelper::loginfo(), 'payment_error', true, false
        );

    }

    /**
     * Delete payment instrument
     * @param $user
     * @param $instrument
     * @param $response
     * @return array
     */
    // Handles credit cards only at this point
    public static function deleteInstrument($user, $instrument, $response)
    {

        // Make sure we have a Stripe Customer on the User
        $customer = self::getStripeCustomer($user, $response);

        if (!PaymentHelper::isValid($customer)) {
            // Handle error
            return PaymentHelper::handleEvent(
                'Missing StripeCustomer instance', PaymentHelper::loginfo(), 'payment_error'
            );
        }

        // Delete the payment instrument
        $_instrument = PayService::cardComponent()->deletePaymentInstrument($user, $instrument, $response);

        if(!PaymentHelper::isValid($_instrument)) {
            // Handle error
            return PaymentHelper::handleEvent(
                'Payment instrument delete failed.', PaymentHelper::loginfo(), 'payment_error', true, false
            );
        }

        $card = PayService::cardComponent()->_deleteSourceCreditCard(["id" => $instrument]);
        if(!$card['success']) {
            // Handle error
            return PaymentHelper::handleEvent(
                'Payment instrument delete failed.', PaymentHelper::loginfo(), 'payment_error', true, false
            );
        }

        // Handle success
        return PaymentHelper::handleEvent(
            'Payment instrument successfully deleted.', PaymentHelper::loginfo(), 'payment_event', true, false
        );

    }

    /**
     * @param User $user
     * @param Response $response
     * @return array
     */
    public static function invoiceSubscription(User $user, Response $response) {
        if(!$user->business->hasStripeSubscription()) {
            return PaymentHelper::handleEvent("Stripe subscription doesn\'t exist.", PaymentHelper::loginfo(), 'payment_event', false, false);
        }
        $subscriptionId = $user->business->getStripeSubscription()->id;
        $subscription = PayService::subscriptionComponent()->updateSubscription(
            $subscriptionId,
            [
                'collection_method' => 'send_invoice',
                'days_until_due' => 30
            ],
            $response
        );
        if(!PaymentHelper::isValid($subscription)) {
            return PaymentHelper::handleEvent("Error updating Stripe subscription: " . $subscriptionId, PaymentHelper::loginfo(), 'payment_error', true, false);
        }
        // The business' payment_type is set to 'subscription'.  Let's set it back to null.
        $user->business->setPaymentType(null);
        return PaymentHelper::handleEvent("Stripe subscription collection method set to 'send_invoice'.", PaymentHelper::loginfo(), 'payment_event', false, false);
    }

    /**
     * @param User $user
     * @param Response $response
     * @return array
     */
    public static function cancelSubscription(User $user, Response $response) {
        if(!$user->business->hasStripeSubscription()) {
            return PaymentHelper::handleEvent("Stripe subscription doesn\'t exist.", PaymentHelper::loginfo(), 'payment_event', false, false);
        }
        $subscriptionId = $user->business->getStripeSubscription()->id;
        $subscription = PayService::subscriptionComponent()->cancelSubscription($subscriptionId,$response);
        if(!PaymentHelper::isValid($subscription)) {
            return PaymentHelper::handleEvent("Error cancelling Stripe subscription: " . $subscriptionId, PaymentHelper::loginfo(), 'payment_error', true, false);
        }
        // The business' payment_type is set to 'subscription'.  Let's set it back to null.
        $user->business->setPaymentType(null);
        return PaymentHelper::handleEvent("Stripe subscription has been cancelled.", PaymentHelper::loginfo(), 'payment_event', false, false);
    }

    private static function handleDefaultSourceUpdate($user, $instrument, $response)
    {

        // Make the instrument the customer's default payment source
        $default_source = is_array($instrument) ? $instrument['data']['id'] : $instrument;
        $customer = PayService::customerComponent()->updateCustomer($user, ["default_source" => $default_source], $response);

        // Verify that the customer's default source was updated
        if (!PaymentHelper::isValid($customer) || $customer['data']['default_source'] !== $default_source) {
            return PaymentHelper::handleEvent(
                'Customer default source update failed', PaymentHelper::loginfo(), 'payment_error'
            );
        }

        return PaymentHelper::handleEvent(
            'Customer default source updated', null, 'payment_event', false, false
        );
    }

    /**
     * Purchase service plan (one-time payment)
     * @param $user
     * @param $plan
     * @param $instrument_token
     * @return array
     */
    public static function purchasePlan($user, $plan, $instrument_type, $instrument_token, $instrument_mode, $response) {

        // Make sure we have a Stripe Customer on the User
        $customer = self::getStripeCustomer($user, $response);

        // Get plan pricing
        $price = StripePrice::where('id', $plan)->first();

        /* BYPASSING THE ONE_TIME FEE, UFN: */
        // Get one time signup fee
        // $oneTimeFee = StripePrice::where('id', self::ONE_TIME_FEE_ID)->first();
        $oneTimeFee = "";

        if(PaymentHelper::isValid($customer) && isset($price) && isset($oneTimeFee)) {

            // Let's assume this is a renewal
            $renew = true;

            // Check the Business' status
            if($user->business->applyOneTimeFee()) {

                // If the Business' status is null, then it shouldn't have an associated ASA.  Let's add the one-time fee.
                /* BYPASSING THE ONE_TIME FEE, UFN: */
                //$chargeAmount = $price->unit_amount + $oneTimeFee->unit_amount;
                $chargeAmount = $price->unit_amount;
                $renew = false;

            } else {

                // No one-time fee.
                $chargeAmount = $price->unit_amount;

            }

            // Perform one-time payment for plan
            $result = self::createStripeCharge($chargeAmount, strtolower(str_replace(" ","-", $price->description)), $instrument_token, $user, $instrument_type, $instrument_mode, $response);

            if(PaymentHelper::isValid($result)) {
                return PaymentHelper::handleEvent(
                    'Charge processing.  Please check back momentarily.', PaymentHelper::loginfo(), 'payment_event', true, false
                );


            }

            // Handle error
            return PaymentHelper::handleEvent(
                'Charge not successful, reason: ' . $result['data'], PaymentHelper::loginfo(), 'payment_error'
            );

        }

        // Handle error
        return PaymentHelper::handleEvent(
            'Missing StripeCustomer instance, StripePrice plan instance, or StripePrice one-time fee instance.',
            PaymentHelper::loginfo(),
            'onetime_failed'
        );

    }

    /**
     * Subscribe to service plan
     * @param $user
     * @param $plan
     * @param $instrument_type
     * @param $instrument_token
     * @param $response
     * @return array
     */
    public static function subscribeToPlan($user, $plan, $coupon, $instrument_type, $instrument_token, $instrument_mode, $response) {

        $business = $user->business;

        // A Business can subscribe if it's 'status' is expired or
        // if it hasn't yet created a StripeSubscription
        if($business->canSubscribe()) {

            // Make sure we have a Stripe Customer on the User.
            $customer = self::getStripeCustomer($user, $response);

            // Get plan pricing
            $price = StripePrice::where('id', $plan)->first();

            // Create the customer's payment instrument.
            if (PaymentHelper::isValid($customer) && isset($price)) {

                $instrument = null;
                if ($instrument_type === "credit_card" && $instrument_mode !== "existing") {
                    $instrument = PayService::cardComponent()->createPaymentInstrument($user, ['source' => $instrument_token], $response);

                    if (!PaymentHelper::isValid($instrument)) {
                        return PaymentHelper::handleEvent(
                            'Create card failed', PaymentHelper::loginfo(), 'payment_failed'
                        );
                    }
                }

                if ($instrument_mode === "existing" && $instrument_token !== $customer['data']->default_source) {

                    $customerData = [
                        $customer['data']->stripe_id,
                        "default_source" => $instrument_token
                    ];

                    $updatedCustomer = PayService::customerComponent()->updateCustomer($user, $customerData, $response);

                    if (!PaymentHelper::isValid($updatedCustomer)) {

                        // Handle error
                        return PaymentHelper::handleEvent(
                            'Customer update failed.', PaymentHelper::loginfo(), 'payment_failed'
                        );

                    }

                }

                // Create the customer's subscription
                // Let's assume we'll be creating a new ASA.
                $renew = false;

                /**
                 * if someone is processing payment BEFORE the asa expiration date, then we create the subscription in a
                 * free trial state that ends upon the asa expiration.
                 */
                $trial_end = "now";
                if(Carbon::now() < $business->asa->expiration) {
                    $trial_end = $business->asa->expiration->timestamp;
                }

                /**
                 * The maxiumum length of a free-trial allowed by Stripe is 2 years.  If the expiration is greater than 2'
                 * years out then let's bail with a message to try again later.
                 */
                if($business->asa->expiration > Carbon::now()->addYears(2))
                {
                    // Handle success
                    return PaymentHelper::handleEvent(
                        'Subscription Early', PaymentHelper::loginfo(), 'recurring_early', true, false
                    );
                }

                if ($business->applyOneTimeFee()) {

                    // create subscription w/ one-time fee
                    $subscription = PayService::subscriptionComponent()
                        ->createSubscription([
                            'customer' => $user->stripe_id,
                            'items' => [['price' => $plan]],
                            /* BYPASSING THE ONE_TIME FEE, UFN:
                            'add_invoice_items' => [['price' => self::ONE_TIME_FEE_ID]], // $2600 one-time fee */
                            'collection_method' => 'charge_automatically',
                            'coupon' => $coupon,
                            'trial_end' => $trial_end
                        ], $response);

                }
                else if ($business->status === "expired" || !$business->hasStripeSubscription()) {

                    // If the Business' is expired, then let's remove any existing subscription.
                    $existingSubscription = StripeSubscription::where('business_id', $business->id)->first();
                    if (isset($existingSubscription)) {
                        $deletedSubscription = $existingSubscription->delete();
                    }

                    // Next let's create a subscription on the plan, not including the one-time fee.
                    if (is_null($existingSubscription) || $deletedSubscription) {

                        $subscription = PayService::subscriptionComponent()
                            ->createSubscription([
                                'customer' => $user->stripe_id,
                                'items' => [['price' => $plan]],
                                'collection_method' => 'charge_automatically',
                                'coupon' => $coupon,
                                'trial_end' => $trial_end
                            ], $response);

                        // This is a renewal
                        $renew = true;

                    } else {

                        // Handle error
                        return PaymentHelper::handleEvent(
                            'Error handling existing subscription.', PaymentHelper::loginfo(), 'recurring_failed'
                        );

                    }

                }

                if (PaymentHelper::isValid($subscription)) {

                    if ($subscription['data']['status'] === 'active' || $subscription['data']['status'] === 'trialing') {

                        // Handle success
                        return PaymentHelper::handleEvent(
                            'Subscription Created', PaymentHelper::loginfo(), 'recurring_succeeded', true, false
                        );

                    }

                }

                // Handle error
                return PaymentHelper::handleEvent(
                    'Subscription couldn\'t be created.', PaymentHelper::loginfo(), 'recurring_failed'
                );

            }

            // Handle error
            return PaymentHelper::handleEvent(
                'Missing Stripe Customer or Stripe Price instance.', PaymentHelper::loginfo(), 'recurring_failed'
            );

        }

        // Handle error
        $eventData = json_encode(["business" => $business->id, "status" => $business->status]);
        return PaymentHelper::handleEvent(
            'Subscription purchase attempt on business id: ' . $eventData, PaymentHelper::loginfo(), 'recurring_failed'
        );

    }

    /**
     * Charge instrument token for one-time payment
     * @param $customer
     * @param $price
     * @param $token
     * @return array|null
     */
    private static function createStripeCharge($price, $description, $token, $user, $instrument_type, $instrument_mode, $response) {

        $responseData = null;

        try {

            $data = [
                'amount' => $price,
                'currency' => 'usd',
                'source' => $token,
                'metadata' => [
                    'business' => $user->business->id,
                    'description' => $description,
                    'payment_type' => 'one-time',
                    'stripe_id' => $user->stripe_id
                ]
            ];

            if ($instrument_mode === "save") {
                $card = true;
                $default_instrument = false;
                $internal = true;
                $instrument = self::addInstrument($user, $token, $default_instrument, $response, $card, $internal);
                if(!PaymentHelper::isValid($instrument)) {
                    throw new \Exception('Error saving payment instrument');
                }
                $data['source'] = $instrument['data']['id'];
            }

            // We'll pass the customer if this is an ach_debit charge, if the save card option was selected, or if
            // this is an existing card
            if($instrument_type === "bank_account" || $instrument_mode === "save" || $instrument_mode === "existing") {
                $data['customer'] = $user->stripe_id;
            }

            // Create the charge
            $result = PaymentHelper::objToArray(StripeClient::getInstance()->charges->create($data));

            // Handle success
            $responseData = PaymentHelper::handleEvent($result, null, 'payment_event');

        } catch(\Exception $e) {

            // Handle Exception
            $responseData = PaymentHelper::handleEvent($e->getMessage(), PaymentHelper::loginfo(), 'payment_exception');

        }

        // Return response status/message
        return $responseData;

    }

    /**
     * Get StripeCustomer
     * @param $user
     * @return array
     */
    private static function getStripeCustomer($user, $response) {

        // Try to get the User's StripeCustomer record
        $customer = PayService::customerComponent()->getCustomer($user);

        // Try to create the StripeCustomer if it doesn't already exist
        if(!PaymentHelper::isValid($customer)) {

            $customerData = [
                "name"  => $user->first_name . " " . $user->last_name,
                "email" => $user->email
            ];

            $customer = PayService::customerComponent()->createCustomer($user, $customerData, $response);

            if(PaymentHelper::isValid($customer)) {

                // Return created customer
                return PaymentHelper::handleEvent($customer, null, 'payment_event');

            }

            // Handle error
            return PaymentHelper::handleEvent(
                'Customer doesn\'t exist and couldn\'t be created.', PaymentHelper::loginfo(), 'payment_failed'
            );

        }

        // Return existing customer
        return $customer;

    }

    /**
     * @param $business
     * @param $response
     */
    public static function createStripeAccount($business, $response) {

        // Create the Business' Stripe Account (Stripe Customer) if it doesn't already exist
        $primaryUser = $business->getPrimaryUser($return_object = true);
        if(!is_null($primaryUser->stripe_id)) {
            return ["success" => false, "skip" => true];
        }
        $stripeCustomer = PayService::customerComponent()->createCustomer(
            $primaryUser,
            [
                'name' => $primaryUser['first_name'] . " " . $primaryUser['last_name'],
                'email' => $primaryUser['email'],
                'description' => "business id: " . $business->id
            ],
            $response
        );
        if(!PaymentHelper::isValid($stripeCustomer)) {
            return PaymentHelper::handleEvent('Stripe customer creation failed for business: ' . $business->id, PaymentHelper::loginfo(), 'payment_error');
        }
        return PaymentHelper::handleEvent(null, null, 'payment_event', false ,false);

    }

    /**
     * Handles adding and renewing BusinessASAs
     * @param $user
     * @param $description
     * @return array
     */
    public static function handleBusinessASA($data)
    {
        $business = null;
        $description = null;
        $renew = null;
        $payment_type = null;

        // This handles the invoice.paid webhook (subscription payments)
        if (isset($data['invoice'])) {

            $invoice = PaymentHelper::objToArray($data['invoice']);

            // Grab the Business
            $subscription = StripeSubscription::where('latest_invoice', $invoice['id'])->first();
            $business = $subscription->business;

            // Determine whether or not this is a renewal (determines the ASA expiration)
            // BusinessASA renewal if business status is expired only.
            if ($business->isRenewal("subscription")) {
                $renew = true;
            } else {
                $renew = false;
            }

            // Get the product description
            $products = $invoice['lines']['data'];
            // If this isn't a renewal, then the first price is the one-time fee.  In that case let's grab the second price.
            $price_id = count($products) < 2 ? $products[0]['price']['id'] : $products[1]['price']['id'];
            $price = StripePrice::where('id', $price_id)->first();
            $description = $price->description;

            // This is a subscription payment
            $payment_type = "subscription";

        } elseif (isset($data['charge'])) {

            $charge = $data['charge'];

            // Grab the Business
            $business = Business::where('id', $charge['metadata']['business'])->first();

            // Determine whether or not this is a renewal (determines the ASA expiration)
            // BusinessASA renewal if business status is active without a StripeSubscription, expired, or renewed.
            if ($business->isRenewal("one_time")) {
                $renew = true;
            } else {
                $renew = false;
            }

            // Get the product description
            $description = $charge['metadata']['description'];

            // This is a one-time payment
            $payment_type = "one_time";

        } else {

            // This handles one-time payments (self::purchasePlan()).
            $business = $data['user']->business;
            $description = $data['description'];
            $renew = $data['renew'];
            $payment_type = $data['payment_type'];

        }

        /** BEGIN TRANSACTION **/
        DB::beginTransaction();

        try {

            $payment_type === "one_time" ?
                self::provisionOneTimeASA($business, $description, $charge) :
                self::provisionAutoPayASA($business, $description, $invoice);

            // Enable Business' Users
            $business->enableUsers();

            // Set the payment type [one_time | subscription]
            $business->payment_type = $payment_type;

            if ($business->save()) {
                $responseData = PaymentHelper::handleEvent('Payment successful, ASA provisioned.', null, 'payment_event');
            } else {
                throw new \Exception('ASA record couldn\'t be created.');
            }

        } catch (QueryException $e) {

            DB::rollback();

            // Handle exception
            $responseData = PaymentHelper::handleEvent($e->getMessage(), PaymentHelper::loginfo(), 'payment_exception');

        } catch (\Exception $e) {

            DB::rollback();

            // Handle exception
            $responseData = PaymentHelper::handleEvent($e->getMessage(), PaymentHelper::loginfo(), 'payment_exception');

        }

        DB::commit();
        /** END TRANSACTION **/

        return $responseData;

    }

    private static function provisionOneTimeASA(&$business, $description, $charge) {

        if($business->isRenewal()) {
            // For one-time purchases on businesses that have a status of expired, one [year|month] (depending on the ASA's
            // billing period) is added to the purchase date.
            $expiration = $business->asa->billingPeriod() === "annual" ? Carbon::now()->addYear() : Carbon::now()->addMonth();
            $business->status = 'renewed';

        } else {
            // For one-time purchases on businesses that have a status of active or renewed, one [year|month] (depending on the ASA's
            // billing period) is added to the current expiration date.
            $expiration = $business->asa->billingPeriod() === "annual" ? $business->asa->expiration->addYear() : $business->asa->expiration->addMonth();
            $business->status = 'active';
        }

        // Advance BusinessASA expiration
        self::advanceASAExpiration(
            [
                'business' => $business,
                'description' => $description,
                'expiration' => $expiration
            ]
        );

    }

    private static function provisionAutoPayASA(&$business, $description, $invoice) {

        // Let's first store the business' current status - before it's changed - for use in determining whether
        // this is a Stripe Smart Retry below ($business->canStripeSmartRetry()).
        $currentStatus = $business->status;

        if($business->isRenewal()) {
            $business->status = 'renewed';
        } else {
            $business->status = 'active';
        }

        $subscription = StripeClient::getInstance()->subscriptions->retrieve($invoice['subscription'],[]);
        $expiration = $business->asa->billingPeriod() === "annual"
            ? Carbon::createFromTimestamp($subscription->billing_cycle_anchor)->addYear()->toDateTimeString()
            : Carbon::createFromTimestamp($subscription->billing_cycle_anchor)->addMonth()->toDateTimeString();

        // if this is a Stripe Smart Retry then let's set the business' status back to the previous state
        if($business->canStripeSmartRetry($currentStatus)) {
            $business->status = $business->previous_status;
        }

        // Advance BusinessASA expiration
        self::advanceASAExpiration(
            [
                'business' => $business,
                'description' => $description,
                'expiration' => $expiration
            ]
        );

    }

    /**
     * Advance BusinessASA expiration
     * @param $data
     * @return array
     */
    private static function advanceASAExpiration($data) {

        try {

            $business = $data['business'];
            $description = $data['description'];
            $expiration = $data['expiration'];

            $result = $business->renewASA([
                'type' => $description,
                'expiration' => $expiration
            ]);

            if($result) {
                return PaymentHelper::handleEvent($result, null, 'payment_event');
            }

            throw new \Exception('Error renewing Business ASA.');


        } catch (\Exception $e) {

            return PaymentHelper::handleEvent($e->getMessage(), PaymentHelper::loginfo(), 'payment_exception');

        }

    }

}
