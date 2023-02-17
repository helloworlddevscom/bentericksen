<?php

namespace Bentericksen\Payment\API;

/**
 * Interface CustomerInterface
 * @package Bentericksen\Payment\API
 */
interface CustomerInterface
{

    /**
     * Create a customer
     * @param $customer
     * @param $customerData
     * @param $response
     * @return mixed
     */
    public function createCustomer($customer, $customerData, $response);

    /**
     * Update a customer
     * @param $customer
     * @param $customerData
     * @param $response
     * @return mixed
     */
    public function updateCustomer($customer, $customerData, $response);

    /**
     * Get a customer
     * @param $customer
     * @return mixed
     */
    public function getCustomer($customer);

    /**
     * Get all customers
     * @param $params
     * @return mixed
     */
    public function getAllCustomers($params);

    /**
     * Delete a customer
     * @param $customer
     * @param $response
     * @return mixed
     */
    public function deleteCustomer($customer, $response);

}
