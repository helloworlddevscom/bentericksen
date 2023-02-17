<?php

namespace Bentericksen\Payment\Subscriptions;

use App\User;
use Bentericksen\Payment\API\SubscriptionInterface;
use Bentericksen\Payment\Clients\StripeClient;
use Bentericksen\Payment\PaymentHelper;
use Bentericksen\Payment\Subscriptions\Models\StripeSubscription as Subscription;
use Bentericksen\ViewAs\ViewAs;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class StripeSubscription implements SubscriptionInterface
{

    /**
     * @var
     */
    private $stripeClient;
    private $customer;

    /**
     * Subscription constructor.
     * @param $client
     */
    public function __construct() {
        $this->stripeClient = StripeClient::getInstance();
        $view_as = new ViewAs();
        $this->customer = $view_as->getUser();
    }

    /**
     * Create a customer subscription
     * @param $subscriptionData
     * @param $response
     * @return false|mixed|string
     */
    public function createSubscription($subscriptionData, $response) {

        $result = PaymentHelper::handlePaymentFunc(
            $this,
            [
                'stripeClient'   => $this->stripeClient,
                'stripeInstance' => 'subscriptions',
                'stripeMethod'   => 'create',
                'stripeArgs'     => [$subscriptionData]
            ],
            $response
        );

        return $result;

    }

    /**
     * Get a subscription
     * @param $subscription
     * @return array|mixed
     */
    public function getSubscription($subscription) {

        $result = Subscription::where('id', $subscription)->first();

        if(isset($result)) {
            return PaymentHelper::handleEvent($result, null, 'payment_event');
        }

        return PaymentHelper::handleEvent(
            'Error retrieving customer\'s subscription: ' . $subscription, PaymentHelper::loginfo(), 'payment_error'
        );

    }

    /**
     * Get all subscriptions
     * @param $params
     * @return array|mixed
     */
    public function getAllSubscriptions() {

        $result = Subscription::all();

        if(isset($result)) {
            return PaymentHelper::handleEvent($result, null, 'payment_event');
        }

        return PaymentHelper::handleEvent('Error retrieving all subscriptions.', PaymentHelper::loginfo(), 'payment_error');


    }

    /**
     * Update a subscription
     * @param $subscription
     * @param $subscriptionData
     * @param $response
     * @return false|mixed|string
     */
    public function updateSubscription($subscription, $subscriptionData, $response) {

        $result = PaymentHelper::handlePaymentFunc(
            $this,
            [
                'stripeClient'   => $this->stripeClient,
                'stripeInstance' => 'subscriptions',
                'stripeMethod'   => 'update',
                'stripeArgs'     => [$subscription, $subscriptionData]
            ],
            $response
        );

        return $result;

    }

    /**
     * Delete a subscription
     * @param $subscription
     * @param $response
     * @return false|mixed|string
     */
    public function cancelSubscription($subscription, $response) {

        $result = PaymentHelper::handlePaymentFunc(
            $this,
            [
                'stripeClient'   => $this->stripeClient,
                'stripeInstance' => 'subscriptions',
                'stripeMethod'   => 'cancel',
                'stripeArgs'     => [$subscription]
            ],
            $response
        );

        return $result;

    }

    /**
     * Update Stripe subscription
     * @param $subscriptionData
     * @return mixed
     */
    public function _updateSubscription($subscriptionData) {

        // Get the subscription record
        $subscription = Subscription::where('id', $subscriptionData['id'])->first();

        // Update subscription status and latest_invoice fields
        $subscription->status = $subscriptionData['status'];
        $subscription->latest_invoice = $subscriptionData['latest_invoice'];

        // Let's remove 'id', 'status', and 'latest_invoice' from $subscriptionData and
        // then save $subscriptionData to a json type db field.
        unset($subscriptionData['id']);
        unset($subscriptionData['status']);
        unset($subscriptionData['latest_invoice']);

        $subscription->data = json_encode($subscriptionData);

        // Save Subscription record
        return $subscription->save();

    }

    /**
     * Store Stripe subscription
     * @param $subscriptionData
     * @return bool
     */
    public function _createSubscription($subscriptionData) {

        // Let's store $subscriptionData['id'], $subscriptionData['status'], and $subscriptionData['latest_invoice'] in
        // their own db fields.  Then, let's remove 'id', 'status', and 'latest_invoice' from $subscriptionData and
        // then save $subscriptionData to a json type db field.
        $subscription = new Subscription();

        $subscription->id = $subscriptionData['id'];
        $subscription->status = $subscriptionData['status'];
        $subscription->latest_invoice = $subscriptionData['latest_invoice'];

        unset($subscriptionData['id']);
        unset($subscriptionData['status']);
        unset($subscriptionData['latest_invoice']);

        $subscription->data = json_encode($subscriptionData);

        // Set StripeSubscription business_id
        if(!is_null($this->customer)) {
            $subscription->business_id = $this->customer->business->id;
        } else {
            $user = User::where('stripe_id', $subscriptionData['customer'])->first();
            $subscription->business_id = $user->business_id;
        }

        // Save StripeSubscription record
        return $subscription->save() === true ? true : false;

    }

    /**
     * Delete Stripe Subscription
     * @param $subscriptionData
     * @return mixed
     */
    public function _cancelSubscription($subscriptionData) {

        $response = null;

        // Delete StripeSubscription record if it exists and handle any errors.
        $subscription = Subscription::where('id', $subscriptionData['id'])->first();
        if($subscription) {
            try {
                $subscription->delete();
            } catch(QueryException $ex) {
                $response = PaymentHelper::handleEvent($ex->message(), PaymentHelper::loginfo(), 'payment_error', true, false);
            } catch(\Exception $ex) {
                $response = PaymentHelper::handleEvent($ex->message(), PaymentHelper::loginfo(), 'payment_error', true, false);
            }
        } else {
            $response = PaymentHelper::handleEvent(null, null, 'payment_event');
        }

        return $response;

    }

}
