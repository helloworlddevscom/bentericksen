<?php


namespace Bentericksen\Payment\API;

/**
 * Interface SubscriptionInterface
 * @package Bentericksen\Payment\API
 */
interface SubscriptionInterface
{

    /** Get a subscription
     * @param $subscription
     * @return mixed
     */
    public function getSubscription($subscription);

    /**
     * Get all subscriptions
     * @param $params
     * @return mixed
     */
    public function getAllSubscriptions();

    /**
     * Update a subscription
     * @param $subscription
     * @param $subscriptionData
     * @param $response
     * @return mixed
     */
    public function updateSubscription($subscription, $subscriptionData, $response);

    /**
     * Cancel a subscription
     * @param $subscription
     * @param $response
     * @return mixed
     */
    public function cancelSubscription($subscription, $response);

}
