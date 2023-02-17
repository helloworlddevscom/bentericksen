<?php

namespace Bentericksen\Payment\Instruments\BankAccounts;

use Bentericksen\Payment\API\PaymentInstrumentInterface;
use Bentericksen\Payment\Clients\StripeClient;
use Bentericksen\Payment\Instruments\BankAccounts\Models\StripeBankAccount as Account;
use Bentericksen\Payment\PaymentHelper;
use Illuminate\Database\QueryException;

/**
 * Class StripeBankAccount
 * @package Bentericksen\Payment\Instruments\BankAccounts
 */
class StripeBankAccount implements PaymentInstrumentInterface
{

    /**
     * Stripe Client
     */
    private $stripeClient;
    private $instrumentType = "BankAccount";

    /**
     * StripeBankAccount constructor.
     */
    public function __construct()
    {
        $this->stripeClient = StripeClient::getInstance();
    }

    /**
     * Create a customer payment instrument
     * @param $customer
     * @param $source
     * @param $response
     * @return false|mixed|string
     */
    public function createPaymentInstrument($customer, $instrumentData, $response) {

        $result = PaymentHelper::handlePaymentFunc(
            $this,
            [
                'stripeClient'   => $this->stripeClient,
                'stripeInstance' => 'customers',
                'stripeMethod'   => 'createSource',
                'stripeArgs'     => [$customer->stripe_id, $instrumentData],
                'instrumentType' => $this->instrumentType
            ],
            $response,
            $customer
        );

        return $result;

    }

    /**
     * Update a customer payment instrument
     * @param $customer
     * @param $instrument
     * @param $instrumentData
     * @param $response
     * @return mixed|void
     */
    public function updatePaymentInstrument($customer, $instrument, $instrumentData, $response)
    {

        $result = PaymentHelper::handlePaymentFunc(
            $this,
            [
                'stripeClient'   => $this->stripeClient,
                'stripeInstance' => 'customers',
                'stripeMethod'   => 'updateSource',
                'stripeArgs'     => [$customer->stripe_id, $instrument, $instrumentData],
                'instrumentType' => $this->instrumentType
            ],
            $response
        );

        return $result;

    }

    /**
     * Get a customer payment instrument
     * @param $customer
     * @param $instrument
     * @return mixed
     */
    public function getPaymentInstrument($customer, $instrument) {

        $result = Account::where(['customer' => $customer->stripe_id, 'id' => $instrument])->first();

        if(isset($result)) {
            return PaymentHelper::handleEvent($result, null, 'payment_event');
        }

        $eventData = json_encode(["customer" => $customer->stripe_id, "instrument" => $instrument]);
        return PaymentHelper::handleEvent(
            'Error retrieving customer\'s bank account\: ' . $eventData, PaymentHelper::loginfo(), 'payment_error'
        );
    }

    /**
     * Get all payment instruments for a customer
     * @param $customer
     * @return mixed
     */
    public function getAllPaymentInstruments($customer) {

        $result = Account::where('business_id',$customer->business->id)->get();

        if(isset($result)) {
            return PaymentHelper::handleEvent($result, null, 'payment_event');
        }

        return PaymentHelper::handleEvent(
            'Error retrieving customer\'s bank accounts.', PaymentHelper::loginfo(), 'payment_error'
        );

    }

    /**
     * Delete a customer payment instrument
     * @param $customer
     * @param $account
     * @param $response
     * @return mixed
     */
    public function deletePaymentInstrument($customer, $instrument, $response) {

        $result = PaymentHelper::handlePaymentFunc(
            $this,
            [
                'stripeClient'   => $this->stripeClient,
                'stripeInstance' => 'customers',
                'stripeMethod'   => 'deleteSource',
                'stripeArgs'     => [$customer->stripe_id, $instrument],
                'instrumentType' => $this->instrumentType
            ],
            $response
        );

        return $result;

    }

    /**
     * Verify BankAccount
     * @param $customer
     * @param $account
     * @param $amounts
     * @param $response
     * @return mixed
     */
    public function verifyBankAccount($customer, $account, $amounts, $response) {

        $result = PaymentHelper::handlePaymentFunc(
            $this,
            [
                'stripeClient'   => $this->stripeClient,
                'stripeInstance' => 'customers',
                'stripeMethod'   => 'verifySource',
                'stripeArgs'     => [$customer->stripe_id, $account, $amounts],
                'instrumentType' => $this->instrumentType
            ],
            $response
        );

        if(PaymentHelper::isValid($result)) {
            return PaymentHelper::handleEvent("Account verified. You may now process payments using this account.", null, 'payment_event', true, false);
        } else {
            return PaymentHelper::handleEvent("Stripe ach verification response invalid", PaymentHelper::loginfo(), 'payment_error', true, false);
        }

    }

    /**
     * Store Stripe BankAccount
     * @param $accountData
     * @return bool
     */
    public function _createSourceBankAccount($accountData, $customer) {

        $account = new Account();

        // Assign Stripe call response object attributes to StripeBankAccount attributes
        foreach($accountData as $attribute => $value) {
            if(PaymentHelper::hasAttribute($account, $attribute)) {
                $account->$attribute = is_array($value) ? json_encode($value) : $value;
            }
        }

        // Set StripeBankAccount User and Business ids
        $account->user_id = $customer->id;
        $account->business_id = $customer->business->id;

        // Save Bank Account record
        return $account->save();

    }

    /**
     * Update Stripe BankAccount
     * @param $accountData
     * @return bool
     */
    public function _updateSourceBankAccount($accountData) {

        $account = Account::where('id', $accountData['id'])->first();

        // Assign Stripe call response object attributes to StripeBankAccount attributes
        foreach($accountData as $attribute => $value) {
            if(PaymentHelper::hasAttribute($account, $attribute)) {
                $account->$attribute = is_array($value) ? json_encode($value) : $value;
            }
        }

        // Save Bank Account record
        return $account->save();

    }

    /**
     * Delete Stripe BankAccount
     * @param $accountData
     * @return mixed
     */
    public function _deleteSourceBankAccount($accountData) {

        $response = null;

        // Delete StripeBankAccount record if it exists and handle any errors.
        $account = Account::where('id', $accountData['id'])->first();
        if($account) {
            try {
                $account->delete();
            } catch(QueryException $ex) {
                return PaymentHelper::handleEvent($ex->message(), PaymentHelper::loginfo(), 'payment_error', true, false);
            } catch(\Exception $ex) {
                return PaymentHelper::handleEvent($ex->message(), PaymentHelper::loginfo(), 'payment_error', true, false);
            }
        } else {
            return PaymentHelper::handleEvent(null, null, 'payment_event');
        }

        return PaymentHelper::handleEvent($account, null, 'payment_event');

    }

}
