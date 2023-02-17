<?php

namespace App\Http\Controllers\Payment;

use App\Facades\PaymentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class SubscriptionController
 * @package App\Http\Controllers\Payment
 */
class SubscriptionController extends Controller
{

    /**
     * Get all subscriptions
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function index()
    {

        return PaymentService::subscriptionComponent()
                ->getAllSubscriptions();

    }

    /**
     * Create a subscription
     * @param Request $request
     * @param Request $response
     * @return Response
     */
    public function store(Request $request, Response $response) {

        // Create a subscription
        return PaymentService::subscriptionComponent()
                ->createSubscription($request->data, $response);

    }

    /**
     * Get a subscription
     * @param $subscription
     * @param Response $response
     * @return mixed
     */
    public function show($subscription, Response $response)
    {

        return PaymentService::subscriptionComponent()
                ->getSubscription($subscription, $response);

    }

    /**
     * Update a subscription
     * @param $subscription
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function update($subscription, Request $request, Response $response)
    {

        return PaymentService::subscriptionComponent()
                ->updateSubscription(
                    $subscription,
                    $request->data,
                    $response
                );

    }

    /**
     * Delete a subscription
     * @param $subscription
     * @param Response $response
     * @return mixed
     */
    public function destroy($subscription, Response $response)
    {

        return PaymentService::subscriptionComponent()
                ->cancelSubscription($subscription, $response);

    }

}
