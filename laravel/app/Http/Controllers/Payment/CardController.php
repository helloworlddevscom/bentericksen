<?php

namespace App\Http\Controllers\Payment;

use Bentericksen\ViewAs\ViewAs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Facades\PaymentService;
use Illuminate\Support\Facades\Auth;

/**
 * Class CardController
 * @package App\Http\Controllers
 */
class CardController extends Controller
{

    /**
     * @var
     */
    private $customer;

    /**
     * CardController constructor.
     */
    public function __construct() {
        $view_as = new ViewAs();
        $this->customer = $view_as->getUser();
    }

    /**
     * Get all payment cards for a customer
     * @return mixed
     */
    public function index()
    {

        return PaymentService::cardComponent()
                ->getAllPaymentInstruments($this->customer);

    }

    /**
     * Create a customer payment card
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function store(Request $request, Response $response)
    {

        // $request->source = Token that can be used in place of a credit card associative array
        // and can be used in any API request
        return PaymentService::cardComponent()
                ->createPaymentInstrument($this->customer, $request->data, $response);


    }

    /**
     * Get a customer payment card
     * @param $instrument
     * @return mixed
     */
    public function show($instrument)
    {

        return PaymentService::cardComponent()
                ->getPaymentInstrument($this->customer, $instrument);

    }

    /**
     * Update a customer payment card
     * @param string $instrument
     * @param Request $request
     * @param Response $response
     * @return false|string
     */
    public function update($instrument, Request $request, Response $response)
    {
        return PaymentService::cardComponent()
            ->updatePaymentInstrument(
                $this->customer,
                $instrument,
                $request->data,
                $response
            );
    }

    /**
     * Delete a customer payment card
     * @param $instrument
     * @param Response $response
     * @return mixed
     */
    public function destroy($instrument, Response $response)
    {

        return PaymentService::cardComponent()
                ->deletePaymentInstrument($this->customer, $instrument, $response);

    }

}
