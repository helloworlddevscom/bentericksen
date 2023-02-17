<?php

namespace App\Http\Controllers\Payment;

use Bentericksen\ViewAs\ViewAs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Facades\PaymentService;
use Illuminate\Support\Facades\Auth;

/**
 * Class BankAccountController
 * @package App\Http\Controllers
 */
class BankAccountController extends Controller
{

    /**
     * @var
     */
    private $customer;

    /**
     * BankAccountController constructor.
     */
    public function __construct() {
        $view_as = new ViewAs();
        $this->customer = $view_as->getUser();
    }

    /**
     * Get all bank accounts for a customer
     * @return mixed
     */
    public function index()
    {

        return PaymentService::bankAccountComponent()
                ->getAllPaymentInstruments($this->customer);

    }

    /**
     * Create a customer bank account
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function store(Request $request, Response $response)
    {

        // $request->source = Token that can be used in place of a bank account associative array
        // and can be used in any API request
        return PaymentService::bankAccountComponent()
                ->createPaymentInstrument($this->customer, $request->data, $response);


    }

    /**
     * Get a customer bank account
     * @param $instrument
     * @return mixed
     */
    public function show($instrument)
    {

        return PaymentService::bankAccountComponent()
                ->getPaymentInstrument($this->customer, $instrument);

    }

    /**
     * Update a customer bank account
     * @param string $instrument
     * @param Request $request
     * @param Response $response
     * @return false|string
     */
    public function update($instrument, Request $request, Response $response)
    {
        return PaymentService::bankAccountComponent()
            ->updatePaymentInstrument(
                $this->customer,
                $instrument,
                $request->data,
                $response
            );
    }

    /**
     * Delete a customer bank account
     * @param $instrument
     * @param Response $response
     * @return mixed
     */
    public function destroy($instrument, Response $response)
    {

        return PaymentService::bankAccountComponent()
                ->deletePaymentInstrument($this->customer, $instrument, $response);

    }

    /**
     * Verify a customer bank account
     * @param $account
     * @param Request $request
     * @param Response $response
     * @return false|string
     */
    public function verifyAccount($account, Request $request, Response $response)
    {

        return PaymentService::bankAccountComponent()
                ->verifyBankAccount($this->customer, $account, $request->data, $response);

    }

}
