<?php


namespace Bentericksen\Payment\Instruments\Cards;

use App\User;
use Bentericksen\Payment\API\PaymentInstrumentInterface;
use Bentericksen\Payment\Instruments\Cards\Models\StripeCard as Card;
use Bentericksen\Payment\PaymentHelper;
use \Illuminate\Database\QueryException;
use Bentericksen\Payment\Clients\StripeClient;

/**
 * Class StripeCard
 * @package Bentericksen\Payment\Instruments\Cards
 */
class StripeCard implements PaymentInstrumentInterface
{

    /**
     * Stripe Client
     */
    private $stripeClient;
    private $instrumentType = "CreditCard";

    /**
     * StripeCard constructor.
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

        $result = Card::where(['customer' => $customer->stripe_id, 'id' => $instrument])->first();

        if(isset($result)) {
            return PaymentHelper::handleEvent($result, null, 'payment_event');
        }

        $eventData = json_encode(["customer" => $customer, "instrument" => $instrument]);
        return PaymentHelper::handleEvent(
            'Error retrieving customer\'s card: ' . $eventData, PaymentHelper::loginfo(), 'payment_error'
        );
    }

    /**
     * Get all payment instruments for a customer
     * @param $customer
     * @return mixed
     */
    public function getAllPaymentInstruments($customer) {

        $result = Card::where('business_id',$customer->business->id)->get();

        if(isset($result)) {
            return PaymentHelper::handleEvent($result, null, 'payment_event');
        }

        return PaymentHelper::handleEvent(
            'Error retrieving customer\'s cards.', PaymentHelper::loginfo(), 'payment_error'
        );

    }

    /**
     * Delete a customer payment instrument
     * @param $customer
     * @param $card
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
     * Store StripeCreditCard
     * @param $cardData
     * @return bool
     */
    public function _createSourceCreditCard($cardData) {

        $card = new Card();

        // Assign Stripe call response object attributes to StripeCard attributes
        foreach($cardData as $attribute => $value) {
            if(PaymentHelper::hasAttribute($card, $attribute)) {
                $card->$attribute = is_array($value) ? json_encode($value) : $value;
            }
        }

        $user = User::where('stripe_id', $cardData['customer'])->first();

        // Set StripeCard User and Business ids
        $card->user_id = $user->id;
        $card->business_id = $user->business->id;

        // Save Card record
        return $card->save();

    }

    /**
     * Update StripeCreditCard
     * @param $cardData
     * @return bool
     */
    public function _updateSourceCreditCard($cardData) {

        $card = Card::where('id', $cardData['id'])->first();

        // Assign Stripe call response object attributes to StripeCard attributes
        foreach($cardData as $attribute => $value) {
            if(PaymentHelper::hasAttribute($card, $attribute)) {
                $card->$attribute = is_array($value) ? json_encode($value) : $value;
            }
        }

        // Save Card record
        return $card->save();

    }

    /**
     * Delete Stripe Card
     * @param $cardData
     * @return mixed
     */
    public function _deleteSourceCreditCard($cardData) {

        $response = null;

        // Delete StripeCard record if it exists and handle any errors.
        $card = Card::where('id', $cardData['id'])->first();
        if($card) {
            try {
                $card->delete();
            } catch(QueryException $ex) {
                return PaymentHelper::handleEvent($ex->message(), PaymentHelper::loginfo(), 'payment_error', true, false);
            } catch(\Exception $ex) {
                return PaymentHelper::handleEvent($ex->message(), PaymentHelper::loginfo(), 'payment_error', true, false);
            }
        } else {
            return PaymentHelper::handleEvent(null, null, 'payment_event');
        }

        return PaymentHelper::handleEvent($card, null, 'payment_event');

    }

}
