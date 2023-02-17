<?php

namespace App\Http\Controllers\Payment;

use App\Facades\PaymentService;
use Bentericksen\ViewAs\ViewAs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class CustomerController
 * @package App\Http\Controllers\Payment
 */
class CustomerController
{

    /**
     * @var
     */
    private $customer;

    /**
     * CustomerController constructor.
     */
    public function __construct() {
        $view_as = new ViewAs();
        $this->customer = $view_as->getUser();
    }

    /**
     * Get all customers
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {

        return PaymentService::customerComponent()
            ->getAllCustomers($request);

    }

    /**
     * Create a customer
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function store(Request $request, Response $response)
    {

        return PaymentService::customerComponent()
            ->createCustomer($this->customer, $request->data, $response);


    }

    /**
     * Get a customer
     * @return mixed
     */
    public function show()
    {

        return PaymentService::customerComponent()
            ->getCustomer($this->customer);

    }

    /**
     * Update a customer
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function update(Request $request, Response $response)
    {
        return PaymentService::customerComponent()
            ->updateCustomer($this->customer, $request->data, $response);
    }

    /**
     * Delete a customer
     * @param Response $response
     * @return mixed
     */
    public function destroy(Response $response)
    {

        return PaymentService::customerComponent()
            ->deleteCustomer($this->customer, $response);

    }

}
