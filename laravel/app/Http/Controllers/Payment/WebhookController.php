<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Bentericksen\Payment\Clients\StripeClient;
use Bentericksen\Payment\Customers\Models\StripeCustomer;
use Bentericksen\Payment\PaymentHelper;
use Bentericksen\Payment\PaymentService;
use App\Facades\PaymentService as StripeService;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Stripe\Customer;

/**
 * Class WebhookController
 * @package App\Http\Controllers\Payment
 */
class WebhookController extends Controller
{

    private $env;

    /**
     * WebhookController constructor.
     */
    public function __construct() {
        $this->env = config('app.env');
    }
    /**
     * Handle Stripe Webhook
     * @param Request $request
     */
    public function handleWebhook(Request $request)
    {
        $webhookSecret = $this->env !== 'production'
            ? config('stripe.webhook.sec_key')
            : config('stripe.webhook.prod.sec_key');

        $result = $this->verifyWebhook(
            $request,
            $request->header('Stripe-Signature'),
            $webhookSecret
        );

        if(PaymentHelper::isValid($result)) {
            return $this->delegateEvent($result);
        }

        PaymentHelper::handleEvent('Webhook verification failed.', PaymentHelper::loginfo(), 'payment_error', true, false);

    }

    private function delegateEvent($result) {


        if($this->env === "testing") {
            $data = json_decode($result['data'], true);
            $event = PaymentHelper::ArrayToObj($data);
        } else {
            $event = $result['data'];
        }

        $object = is_object($event->data->object) ? PaymentHelper::objToArray($event->data->object) : $event->data->object;

        // Handle the event
        switch ($event->type) {

            // Invoices
            case 'invoice.created':
                $invoice = $object; // contains a Stripe Invoice object
                $this->handleInvoiceCreated($invoice);
                break;
            case 'invoice.payment_failed':
                $invoice = $object; // contains a Stripe Invoice object
                $this->handlePaymentFailed($invoice);
                break;
            case 'invoice.paid':
                $invoice = $object; // contains a Stripe Invoice object
                $this->handleInvoicePaid($invoice);
                break;
            case 'invoice.upcoming':
                $invoice = $object; // contains a Stripe Invoice object
                $this->handleInvoiceUpcoming($invoice);
                break;

            // Charges
            case 'charge.failed':
                $charge = $object; // contains a Stripe Invoice object
                $this->handleChargeFailed($charge);
                break;
            case 'charge.succeeded':
                $charge = $object; // contains a Stripe Invoice object
                $this->handleChargeSucceeded($charge);
                break;

            // Customers
            case 'customer.updated':
                $customer = $object; // contains a Stripe Customer object
                $this->handleCustomerUpdated($customer);
                break;
            case 'customer.deleted':
                $customer = $object; // contains a Stripe Customer object
                $this->handleCustomerDeleted($customer);
                break;

                // Customer Discounts
            case 'customer.discount.created':
                $discount = $object; // contains a Stripe Discount object
                $this->handleCustomerDiscountCreated($discount);
                break;
            case 'customer.discount.updated':
                $discount = $object; // contains a Stripe Discount object
                $this->handleCustomerDiscountUpdated($discount);
                break;
            case 'customer.discount.deleted':
                $discount = $object; // contains a Stripe Discount object
                $this->handleCustomerDiscountDeleted($discount);
                break;

            // Sources
            case 'customer.source.created':
                $source = $object; // contains a Stripe Source object
                $this->handleCustomerSourceCreated($source);
                break;
            case 'customer.source.updated':
                $source = $object; // contains a Stripe Source object
                $this->handleCustomerSourceUpdated($source);
                break;
            case 'customer.source.deleted':
                $source = $object; // contains a Stripe Source object
                $this->handleCustomerSourceDeleted($source);
                break;
            case 'customer.source.expiring':
                $source = $object; // contains a Stripe Source object
                $this->handleCustomerSourceExpiring($source);
                break;

            // Subscriptions
            case 'customer.subscription.created':
                $subscription = $object; // contains a Stripe Subscription object
                $this->handleCustomerSubscriptionCreated($subscription);
                break;
            case 'customer.subscription.updated':
                $subscription = $object; // contains a Stripe Subscription object
                $this->handleCustomerSubscriptionUpdated($subscription);
                break;
            case 'customer.subscription.deleted':
                $subscription = $object; // contains a Stripe Subscription object
                $this->handleCustomerSubscriptionDeleted($subscription);
                break;

            // Products
            case 'product.created':
                $product = $object; // contains a Stripe Product object
                $this->handleProductCreated($product);
                break;
            case 'product.updated':
                $product = $object; // contains a Stripe Product object
                $this->handleProductUpdated($product);
                break;
            case 'product.deleted':
                $product = $object; // contains a Stripe Product object
                $this->handleProductDeleted($product);
                break;

        }

    }

    // Invoices
    /**
     * Handles invoice.created
     * @param $invoice
     * @return \Illuminate\Http\Response
     */
    private function handleInvoiceCreated($invoice) {

        /*
         If Stripe fails to receive a successful response to invoice.created, then finalizing all invoices with
         automatic collection will be delayed for up to 72 hours.
         */
        return response()->noContent(200);

    }

    /**
     * Handles invoice.payment_failed
     * @param $invoice
     * @return \Illuminate\Http\Response
     */
    private function handlePaymentFailed($invoice) {

        // Log event
        PaymentHelper::handleEvent(
            'invoice.payment_failed: ' . $invoice['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // Notify payment failed
        $result = PaymentHelper::handlePaymentFailed($invoice);

        // Handle response
        return $this->handleResponse($result);

    }

    /**
     * Handles invoice.paid
     * @param $invoice
     */
    private function handleInvoicePaid($invoice) {

        // Handle business ASA
        $result = PaymentService::handleBusinessASA(['invoice' => $invoice]);

        // Handle response
        return $this->handleResponse($result);

    }

    /**
     * Handles invoice.upcoming
     * @param $invoice
     *
     * Occurs X number of days before a subscription is scheduled to create an invoice that is automatically
     * charged—where X is determined by your subscriptions settings (Stripe dashboard).
     */
    private function handleInvoiceUpcoming($invoice) {

        // Log event
        PaymentHelper::handleEvent(
            'invoice.upcoming: ' . $invoice['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // Notify payment upcoming
        $result = PaymentHelper::handleUpcomingRenewal($invoice);

        // Handle response
        return $this->handleResponse($result);

    }

    // Charges
    /**
     * Handles charge.failed
     * @param $_charge
     */
    private function handleChargeFailed($_charge) {

        $charge = PaymentHelper::objToArray($_charge);

        // Log event
        PaymentHelper::handleEvent(
            'charge.failed: ' . $charge['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // Notify charge failed
        $result = PaymentHelper::handlePaymentFailed($charge);

        return $this->handleResponse($result);

    }

    /**
     * Handles charge.succeeded
     * @param $charge
     * @return \Illuminate\Http\Response
     */
    private function handleChargeSucceeded($_charge) {

        // For one-time payments only. Subscription payments are handled by invoice.paid
        $charge = PaymentHelper::objToArray($_charge);

        // Log event
        PaymentHelper::handleEvent(
            'charge.succeeded: ' . $charge['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // Notify charge succeeded
        PaymentHelper::handlePaymentSucceeded($charge);

        if(isset($charge['metadata']['payment_type'])) {
            $result = PaymentService::handleBusinessASA(['charge' => $charge]);
        } else {
            $result = PaymentHelper::handleEvent(null, null, 'payment_event');
        }

        // Handle response
        return $this->handleResponse($result);

    }

    // Customers
    /**
     * Handle customer.updated
     * @param $_customer
     * @return \Illuminate\Http\Response
     */
    private function handleCustomerUpdated($_customer) {

        $customer = PaymentHelper::objToArray($_customer);

        // Log event
        PaymentHelper::handleEvent(
            'customer.updated: ' . $customer['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // We need an associated User to update the StripeCustomer
        $user = User::where('stripe_id', $customer['id'])->first();

        // Persist event data
        if(isset($user)) {
            $result = $this->persistEventData(StripeService::customerComponent(), '_updateCustomer', [$customer, $user]);
        } else {
            $result = PaymentHelper::handleEvent(
                'No User associated with StripeCustomer ' . $customer['id'], PaymentHelper::loginfo(), 'payment_error'
            );
        }

        // Handle response
        return $this->handleResponse($result);


    }

    /**
     * Handle customer.deleted
     * @param $_customer
     * @return \Illuminate\Http\Response
     */
    private function handleCustomerDeleted($_customer) {

        $customer = PaymentHelper::objToArray($_customer);

        // Log event
        PaymentHelper::handleEvent(
            'customer.deleted: ' . $customer['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // Persist event data
        $result = $this->persistEventData(StripeService::customerComponent(), '_deleteCustomer', [$customer]);

        // Handle response
        return $this->handleResponse($result);


    }

    // Discounts
    /**
     * Handle customer.discount.created
     * @param $_discount
     * @return \Illuminate\Http\Response
     */
    private function handleCustomerDiscountCreated($_discount) {

        $discount = PaymentHelper::objToArray($_discount);

        // Log event
        PaymentHelper::handleEvent(
            'customer.discount.created: ' . $discount['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // We need an associated User to update the StripeCustomer
        $user = User::where('stripe_id', $discount['customer'])->first();

        // Persist event data
        if(isset($user)) {
            $result = $this->persistEventData(
                StripeService::customerComponent(), '_updateCustomer', [['discount' => $_discount], $user]);
        } else {
            $result = PaymentHelper::handleEvent(
                'No User associated with StripeCustomer ' . $discount['customer'], PaymentHelper::loginfo(), 'payment_error'
            );
        }

        // Handle response
        return $this->handleResponse($result);

    }

    private function handleCustomerDiscountUpdated($_discount) {

        $discount = PaymentHelper::objToArray($_discount);

        // Log event
        PaymentHelper::handleEvent(
            'customer.discount.updated: ' . $discount['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // We need an associated User to update the StripeCustomer
        $user = User::where('stripe_id', $discount['customer'])->first();

        // Persist event data
        if(isset($user)) {
            $result = $this->persistEventData(
                StripeService::customerComponent(), '_updateCustomer', [['discount' => $_discount], $user]);
        } else {
            $result = PaymentHelper::handleEvent(
                'No User associated with StripeCustomer ' . $discount['customer'], PaymentHelper::loginfo(), 'payment_error'
            );
        }

        // Handle response
        return $this->handleResponse($result);

    }

    private function handleCustomerDiscountDeleted($_discount) {

        $discount = PaymentHelper::objToArray($_discount);

        // Log event
        PaymentHelper::handleEvent(
            'customer.discount.deleted: ' . $discount['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // We need an associated User to update the StripeCustomer
        $user = User::where('stripe_id', $discount['customer'])->first();

        // Persist event data
        if(isset($user)) {
            $result = $this->persistEventData(
                StripeService::customerComponent(), '_updateCustomer', [['discount' => null], $user]
            );
        } else {
            $result = PaymentHelper::handleEvent(
                'No User associated with StripeCustomer ' . $discount['customer'], PaymentHelper::loginfo(), 'payment_error'
            );
        }

        // Handle response
        return $this->handleResponse($result);

    }

    // Sources
    /**
     * Handle customer.source.created
     * @param $_source
     * @return \Illuminate\Http\Response
     */
    private function handleCustomerSourceCreated($_source) {

        $source = PaymentHelper::objToArray($_source);

        // Log event
        PaymentHelper::handleEvent(
            'customer.source.created: ' . $source['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // Persist event data
        $result = $this->persistEventData(StripeService::cardComponent(), '_createSourceCreditCard', [$source]);

        // Handle response
        return $this->handleResponse($result);

    }

    /**
     * Handle customer.source.updated
     * @param $_source
     * @return \Illuminate\Http\Response
     */
    private function handleCustomerSourceUpdated($_source) {

        $source = PaymentHelper::objToArray($_source);

        // Log event
        PaymentHelper::handleEvent(
            'customer.source.updated: ' . $source['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // Persist event data
        if($source['object'] === "bank_account") {
            $result = $this->persistEventData(StripeService::bankAccountComponent(), '_updateSourceBankAccount', [$source]);
        } elseif($source['object'] === "card") {
            $result = $this->persistEventData(StripeService::cardComponent(), '_updateSourceCreditCard', [$source]);
        }

        // Handle response
        return $this->handleResponse($result);

    }

    /**
     * Handle customer.source.deleted
     * @param $_source
     * @return \Illuminate\Http\Response
     */
    private function handleCustomerSourceDeleted($_source) {

        $source = PaymentHelper::objToArray($_source);

        // Log event
        PaymentHelper::handleEvent(
            'customer.source.deleted: ' . $source['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // Persist event data
        if($source['object'] === "bank_account") {
            $result = $this->persistEventData(StripeService::bankAccountComponent(), '_deleteSourceBankAccount', [$source]);
        } elseif($source['object'] === "card") {
            $result = $this->persistEventData(StripeService::cardComponent(), '_deleteSourceCreditCard', [$source]);
        }

        // Handle response
        return $this->handleResponse($result);

    }

    /**
     * Handle customer.source.expiring
     * @param $_source
     * @return \Illuminate\Http\Response
     */
    private function handleCustomerSourceExpiring($_source) {

        $source = PaymentHelper::objToArray($_source);

        // Log event
        PaymentHelper::handleEvent(
            'customer.source.expiring: ' . $source['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // Notify payment method expiring
        $result = PaymentHelper::handlePaymentMethodExpiring($source);

        // Handle response
        return $this->handleResponse($result);

    }

    // Subscriptions
    /**
     * Handle customer.subscription.created
     * @param $_subscription
     * @return \Illuminate\Http\Response
     * @throws \Stripe\Exception\ApiErrorException
     */
    private function handleCustomerSubscriptionCreated($_subscription)
    {

        $subscription = PaymentHelper::objToArray($_subscription);

        // Log event
        PaymentHelper::handleEvent(
            'customer.subscription.created: ' . $subscription['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // Persist event data
        $result = $this->persistEventData(StripeService::subscriptionComponent(), '_createSubscription', [$subscription]);

        // Handle response
        return $this->handleResponse($result);

    }

    /**
     * Handle customer.subscription.updated
     * @param $_subscription
     * @return \Illuminate\Http\Response
     * @throws \Stripe\Exception\ApiErrorException
     */
    private function handleCustomerSubscriptionUpdated($_subscription)
    {

        $response = null;

        $subscription = PaymentHelper::objToArray($_subscription);

        // Log event
        $eventData = 'customer.subscription.updated: ' . $subscription['id'] . ' ' . $subscription['status'];
        PaymentHelper::handleEvent($eventData, PaymentHelper::loginfo(), 'payment_event', true, false);

        // Check Invoice for 'uncollectible' status
        $invoice = PaymentHelper::objToArray(StripeClient::getInstance()->invoices->retrieve($subscription['latest_invoice']));

        // -- Subscription status is set to 'canceled' and Invoice status is set to 'uncollectible' upon final failed
        // -- Smart Retry (https://stripe.com/docs/billing/automatic-collection#smart-retries).
        // If Invoice status is 'uncollectible', add the invoice_status property to the $subscription object (just to make it
        // available) and set to 'uncollectible', since we aren't storing invoices yet.
        if ($invoice['status'] === "uncollectible")
            $subscription['invoice_status'] = "uncollectible";

        $result = $this->persistEventData(StripeService::subscriptionComponent(), '_updateSubscription', [$subscription]);

        return $this->handleResponse($result);

    }

    /**
     * Handle customer.subscription.deleted
     * @param $_subscription
     * @return \Illuminate\Http\Response
     */
    private function handleCustomerSubscriptionDeleted($_subscription) {

        $subscription = PaymentHelper::objToArray($_subscription);

        // Log event
        PaymentHelper::handleEvent(
            'customer.subscription.deleted: ' . $subscription['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // Persist event data
        $result = $this->persistEventData(StripeService::subscriptionComponent(), '_cancelSubscription', [$subscription]);

        // Handle response
        return $this->handleResponse($result);

    }

    // Products
    /**
     * Handles product.created
     * @param $_product
     * @return \Illuminate\Http\Response
     */
    private function handleProductCreated($_product) {

        $product = PaymentHelper::objToArray($_product);

        // Log event
        PaymentHelper::handleEvent(
            'product.created: ' . $product['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // Persist event data
        $result = $this->persistEventData(StripeService::productComponent(), '_createProduct', [$product]);

        // Handle response
        return $this->handleResponse($result);

    }

    /**
     * Handles product.updated
     * @param $_product
     * @return \Illuminate\Http\Response
     */
    private function handleProductUpdated($_product) {

        $product = PaymentHelper::objToArray($_product);

        // Log event
        PaymentHelper::handleEvent(
            'product.updated: ' . $product['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // Persist event data
        $result = $this->persistEventData(StripeService::productComponent(), '_updateProduct', [$product]);

        // Handle response
        return $this->handleResponse($result);

    }

    /**
     * Handles product.deleted
     * @param $_product
     * @return \Illuminate\Http\Response
     */
    private function handleProductDeleted($_product) {

        $product = PaymentHelper::objToArray($_product);

        // Log event
        PaymentHelper::handleEvent(
            'product.deleted: ' . $product['id'], PaymentHelper::loginfo(), 'payment_event', true, false
        );

        // Persist event data
        $result = $this->persistEventData(StripeService::productComponent(), '_deleteProduct', [$product]);

        // Handle response
        return $this->handleResponse($result);

    }

    /**
     * Verify Stripe Webhook Signature
     * @param $payload
     * @param $headerSig
     * @param $webhookSec
     * @return array
     */
    private function verifyWebhook($payload, $headerSig, $webhookSec) {

        try {

            if($this->env === "testing") {
                $data = $payload->getContent();
                return PaymentHelper::handleEvent(PaymentHelper::objToArray($data), null, 'payment_event');
            }

            $event = \Stripe\Webhook::constructEvent($payload->getContent(), $headerSig, $webhookSec);
            return PaymentHelper::handleEvent($event, null, 'payment_event');

        } catch(\UnexpectedValueException $e) {

            // Invalid payload
            return PaymentHelper::handleEvent($e->getMessage(), PaymentHelper::loginfo(), 'payment_exception');

        } catch(\Stripe\Exception\SignatureVerificationException $e) {

            // Invalid signature
            $result = PaymentHelper::handleEvent($e->getMessage(), PaymentHelper::loginfo(), 'payment_exception');

        }

    }

    /**
     * Persists event data
     * @param $obj
     * @param $persistMethod
     * @param $persistArgs
     * @return array
     */
    private function persistEventData($obj, $persistMethod, $persistArgs) {

        try {

            // Persist the event data to the database and handle any errors
            $result = call_user_func_array([$obj, $persistMethod], $persistArgs);

            if($result) {
                $responseData = PaymentHelper::handleEvent($result, null);
            } else {
                throw new \Exception('Unable to persist event data: ' . $persistMethod);
            }


        } catch (QueryException $e) {

            // Handle Query exception
            $responseData =  PaymentHelper::handleEvent($e->getMessage(), PaymentHelper::loginfo(), 'payment_exception');

        } catch (\Exception $e) {

            // Handle exception
            $responseData = PaymentHelper::handleEvent($e->getMessage(), PaymentHelper::loginfo(), 'payment_exception');

        }

        return $responseData;

    }

    /**
     * Handle response
     * @param $result
     * @return \Illuminate\Http\Response
     */
    private function handleResponse($result) {

        if(PaymentHelper::isValid($result)) {
            $response = response()->noContent(200);
        } else {
            $response = response()->noContent(500);
        }

        return $response;

    }

}
