<?php


namespace Bentericksen\Payment\API;

/**
 * Interface PaymentInstrumentInterface
 * @package Bentericksen\Payment
 */
interface PaymentInstrumentInterface
{
    /**
     * Create a customer payment instrument
     * @param $customer
     * @param $token
     * @return mixed
     */
    public function createPaymentInstrument($customer, $instrumentData, $response);

    /**
     * Update a customer payment instrument
     * @param $customer
     * @param $instrument
     * @param $instrumentData
     * @param $response
     * @return mixed
     */
    public function updatePaymentInstrument($customer, $instrument, $instrumentData, $response);

    /**
     * Get a customer payment instrument
     * @param $customer
     * @param $instrument
     * @return mixed
     */
    public function getPaymentInstrument($customer, $instrument);

    /**
     * Get all payment instruments for a customer
     * @param $customer
     * @return mixed
     */
    public function getAllPaymentInstruments($customer);

    /**
     * Delete a customer payment instrument
     * @param $customer
     * @param $instrument
     * @return mixed
     */
    public function deletePaymentInstrument($customer, $instrument, $response);

}
