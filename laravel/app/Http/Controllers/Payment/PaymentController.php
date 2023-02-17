<?php

namespace App\Http\Controllers\Payment;

use App\Business;
use App\Facades\PaymentService;
use App\User;
use Bentericksen\Payment\Clients\StripeClient;
use Bentericksen\Payment\PaymentService as PayService;
use App\Http\Controllers\Controller;
use Bentericksen\ViewAs\ViewAs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Bentericksen\Payment\PaymentHelper;

class PaymentController extends Controller
{

    /**
     * @var User
     */
    private $user;

    /**
     * @var User
     */
    private $actualUser;

    /**
     * @var Business
     */
    private $business;

    /**
     * PaymentController constructor.
     */
    public function __construct() {
        $view_as = new ViewAs();
        $this->user = $view_as->getUser();
        $this->actualUser = $view_as->getActualUser();
        $this->business = $this->user->business;
    }

    public function setupPayments()
    {

        // Get payment UI setup data
        $business = $this->business;
        $apply_fee = $business->applyOneTimeFee();
        $access = $this->user->getPaymentAccess(); // actual users access
        $cards = $access === 'full' ? PayService::getSortedCustomerCards($business->getPrimaryUser($return_object = true)) : [];
        $accounts = $access === 'full' ? PayService::getSortedCustomerAccounts($business->getPrimaryUser($return_object = true)) : [];
        $coupon = $access === 'full' ? PayService::getCoupon($business->getPrimaryUser($return_object = true)) : [];
        $plans = PayService::getPlans();


        // Prepare data for view
        $data = [
            'data' => [
                'business_status' => $business->status,
                'apply_fee' => $apply_fee,
                'payment_type' => $business->payment_type,
                'asa_type' => $business->asa->type,
                'cards' => $cards,
                'accounts' => $accounts,
                'plans' => $plans,
                'coupon' => $coupon,
                'payment_access' => $access === 'full',
                'user_role' => strtolower($this->actualUser->getRole())
            ]
        ];

        // return data
        return response()->json($data);

    }

    /**
     * Create the payment transaction
     * @param Request $request
     */
    public function createTransaction(Request $request, Response $response) {

        // Purchase or subscribe to plan depending on 'payment_type'.
        if($request->payment_type === 'one_time') {

            return PayService::purchasePlan(
                $this->business->getPrimaryUser($return_object = true),
                $request->plan,
                $request->instrument_type,
                $request->token,
                $request->instrument_mode,
                $response
            );

        }
        if($request->payment_type === 'subscription') {

            return PayService::subscribeToPlan(
                $this->business->getPrimaryUser($return_object = true),
                $request->plan,
                $request->coupon,
                $request->instrument_type,
                $request->token,
                $request->instrument_mode,
                $response
            );

        }

        // Handle error if 'payment_type' parameter is missing.
        return PaymentHelper::handleEvent("Missing 'payment_type' parameter.", PaymentHelper::loginfo(), 'payment_error');

    }

    /**
     * Add Payment Instrument
     * @param Request $request
     * @param Response $response
     * @return array
     */
    public function addInstrument(Request $request, Response $response) {

        return PayService::addInstrument(
            $this->business->getPrimaryUser($return_object = true),
            $request->token,
            $request->default_instrument,
            $response,
            $request->card
        );

    }

    /**
     * Update Payment Instrument
     * @param Request $request
     * @param Response $response
     * @return array
     */
    // Handles updating credit cards only at this point
    public function updateInstrument(Request $request, Response $response) {

        return PayService::updateInstrument(
            $this->business->getPrimaryUser($return_object = true),
            $request->instrument,
            $request->instrument_data,
            $request->default_instrument,
            $response
        );

    }

    /**
     * Delete Payment Instrument
     * @param Request $request
     * @param Response $response
     * @return array
     */
    // Handles deleting credit cards only at this point
    public function deleteInstrument(Request $request, Response $response) {

        return PayService::deleteInstrument(
            $this->business->getPrimaryUser($return_object = true),
            $request->instrument,
            $response
        );

    }

    /**
     * @param Response $response
     * @return array
     */
    public function invoiceSubscription(Response $response) {

        return PayService::invoiceSubscription(
            $this->business->getPrimaryUser($return_object = true),
            $response
        );

    }

    /**
     * @param Response $response
     * @return array
     */
    public function cancelSubscription(Response $response) {

        return PayService::cancelSubscription(
            $this->business->getPrimaryUser($return_object = true),
            $response
        );

    }

}
