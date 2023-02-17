<?php


namespace Bentericksen\Payment\Customers;

use Bentericksen\Payment\API\CustomerInterface;
use Bentericksen\Payment\Clients\StripeClient;
use Bentericksen\Payment\PaymentHelper;
use Bentericksen\Payment\Customers\Models\StripeCustomer as Customer;
use Illuminate\Database\QueryException;
use App\User;
use Illuminate\Support\Facades\DB;

/**
 * Class StripeCustomer
 * @package Bentericksen\Payment\Customers
 */
class StripeCustomer implements CustomerInterface
{

    /**
     * @var
     */
    private $stripeClient;

    /**
     * Customer constructor.
     */
    public function __construct() {
        $this->stripeClient = StripeClient::getInstance();
    }

    /**
     * Create a customer
     * @param $customer
     * @param $customerData
     * @param $response
     * @return false|mixed|string
     */
    public function createCustomer($customer, $customerData, $response) {

        $result = PaymentHelper::handlePaymentFunc(
            $this,
            [
                'stripeClient'   => $this->stripeClient,
                'stripeInstance' => 'customers',
                'stripeMethod'   => 'create',
                'stripeArgs'     => [$customerData]
            ],
            $response,
            $customer
        );

        return $result;

    }

    /**
     * Get a customer
     * @param $customer
     * @return array|mixed
     */
    public function getCustomer($customer) {

        $result = Customer::where('user_id', $customer->id)->first();

        if(isset($result)) {
            return PaymentHelper::handleEvent($result, null, 'payment_event');
        }

        return PaymentHelper::handleEvent(
            'Customer not found: ' . $customer->id, PaymentHelper::loginfo(), 'payment_error'
        );

    }

    /**
     * Get all customers
     * @param null $params
     * @return array|mixed
     */
    public function getAllCustomers($params = null) {

        $result = Customer::all();

        if(isset($result)) {
            return PaymentHelper::handleEvent($result, null, 'payment_event');
        }

        return PaymentHelper::handleEvent('Error retrieving customers', PaymentHelper::loginfo(), 'payment_error');

    }

    /**
     * Update a customer
     * @param $customer
     * @param $customerData
     * @param $response
     * @return false|mixed|string
     */
    public function updateCustomer($customer, $customerData, $response) {

        $result = PaymentHelper::handlePaymentFunc(
            $this,
            [
                'stripeClient'   => $this->stripeClient,
                'stripeInstance' => 'customers',
                'stripeMethod'   => 'update',
                'stripeArgs'     => [$customer->stripe_id, $customerData]
            ],
            $response,
            $customer
        );

        return $result;

    }

    /**
     * Delete a customer
     * @param $customer
     * @param $response
     * @return false|mixed|string
     */
    public function deleteCustomer($customer, $response) {

        $result = PaymentHelper::handlePaymentFunc(
            $this,
            [
                'stripeClient'   => $this->stripeClient,
                'stripeInstance' => 'customers',
                'stripeMethod'   => 'delete',
                'stripeArgs'     => [$customer->stripe_id]
            ],
            $response
        );

        return $result;

    }

    /**
     * Store StripeCustomer
     * @param $customerData
     * @param $user
     * @return bool
     */
    public function _createCustomer($customerData, $user) {

        $customer = new Customer();

        // Assign Stripe call response object attributes to StripeCustomer attributes
        foreach($customerData as $attribute => $value) {
            if(PaymentHelper::hasAttribute($customer, $attribute)) {
                $customer->$attribute = is_array($value) ? json_encode($value) : $value;
            }
        }

        // Set Customer user_id
        $customer->user_id = $user->id;

        // Set User stripe_id
        $user->stripe_id = $customerData['id'];

        // Save Customer/User record
        return $customer->save() && $user->save() === true ? true : false;

    }

    /**
     * Update StripeCustomer
     * @param $customerData
     * @param $customer
     * @return mixed
     */
    public function _updateCustomer($customerData, $customer) {

        $customer = Customer::where('user_id', $customer->id)->first();

        // Assign Stripe call response object attributes to StripeCustomer attributes
        foreach($customerData as $attribute => $value) {
            if(PaymentHelper::hasAttribute($customer, $attribute)) {
                $customer->$attribute = is_array($value) ? json_encode($value) : $value;
            }
        }

        // Save Customer record
        return $customer->save();

    }

    /**
     * Delete StripeCustomer
     * @param $customer
     */
    public function _deleteCustomer($customer) {

        $user = User::where('stripe_id', $customer['id'])->first();
        $customer = $user->customer;

        if(isset($customer) && isset($user)) {

            /** BEGIN TRANSACTION **/
            DB::beginTransaction();

            try {

                // Delete Customer record
                $customer->delete();
                // Remove stripe_id from User
                $user->stripe_id = null;
                $user->status = 'disabled';
                $user->save();

            } catch (QueryException $ex) {
                DB::rollBack();
                return PaymentHelper::handleEvent($ex->message(), PaymentHelper::loginfo(), 'payment_error', true, false);
            } catch (\Exception $ex) {
                DB::rollback();
                return PaymentHelper::handleEvent($ex->message(), PaymentHelper::loginfo(), 'payment_error', true, false);
            }

            DB::commit();
            /** END TRANSACTION **/

            // Handles success
            return PaymentHelper::handleEvent(null, null, 'payment_event', false, false);

        }

        // No matching StripeCustomer in db, we can return success here.
        // Handle success
        return PaymentHelper::handleEvent(null, null, 'payment_event', false, false);

    }

}
