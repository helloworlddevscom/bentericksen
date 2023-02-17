<?php


namespace Bentericksen\Payment\Products;

use Bentericksen\Payment\API\ProductInterface;
use Bentericksen\Payment\Clients\StripeClient;
use Bentericksen\Payment\Products\Models\StripeProduct as Product;
use Bentericksen\Payment\PaymentHelper;
use Illuminate\Database\QueryException;

/**
 * Class StripeProduct
 * @package Bentericksen\Payment\Products
 */
class StripeProduct implements ProductInterface
{

    /**
     * @vars
     */
    private $stripeClient;

    /**
     * Product constructor.
     * @param $client
     */
    public function __construct() {
        $this->stripeClient = StripeClient::getInstance();
    }

    /**
     * Create a Stripe Product
     * @param $productData
     * @param $response
     * @return false|mixed|string
     */
    public function createProduct($productData, $response) {

        $result = PaymentHelper::handlePaymentFunc(
            $this,
            [
                'stripeClient'   => $this->stripeClient,
                'stripeInstance' => 'products',
                'stripeMethod'   => 'create',
                'stripeArgs'     => [$productData]
            ],
            $response
        );

        return $result;

    }

    /**
     * Update a Stripe Product
     * @param $product
     * @param $metaData
     * @param $response
     * @return false|mixed|string
     */
    public function updateProduct($product, $metaData, $response)
    {

        $result = PaymentHelper::handlePaymentFunc(
            $this,
            [
                'stripeClient'   => $this->stripeClient,
                'stripeInstance' => 'products',
                'stripeMethod'   => 'update',
                'stripeArgs'     => [$product, $metaData]
            ],
            $response
        );

        return $result;

    }

    /**
     * Get a Stripe Product
     * @param $product
     * @return array|mixed
     */
    public function getProduct($product) {

        $result = Product::where('id', $product)->first();

        if(isset($result)) {

            return PaymentHelper::handleEvent($result, null, 'payment_event');

        }

        return PaymentHelper::handleEvent(
            'Error retrieving product: ' . $product, PaymentHelper::loginfo(), 'payment_error'
        );

    }

    /**
     * Get all Stripe Products
     * @param $params
     * @return array|mixed
     */
    public function getAllProducts() {

        $result = Product::all();

        if(isset($result)) {

            return PaymentHelper::handleEvent($result, null, 'payment_event');

        }

        return PaymentHelper::handleEvent(
            'Error retrieving products', PaymentHelper::loginfo(), 'payment_error'
        );

    }

    /**
     * Delete a Stripe Product
     * @param $product
     * @param $response
     * @return false|mixed|string
     */
    public function deleteProduct($product, $response) {

        $result = PaymentHelper::handlePaymentFunc(
            $this,
            [
                'stripeClient'   => $this->stripeClient,
                'stripeInstance' => 'products',
                'stripeMethod'   => 'delete',
                'stripeArgs'     => [$product]
            ],
            $response
        );

        return $result;

    }

    /**
     * Store a StripeProduct
     * @param $productData
     * @return bool
     */
    public function _createProduct($productData) {

        $product = new Product();

        // Assign Stripe call response object attributes to StripeProduct attributes
        foreach($productData as $attribute => $value) {
            if(PaymentHelper::hasAttribute($product, $attribute)) {
                $product->$attribute = is_array($value) ? json_encode($value) : $value;
            }
        }

        // Save Product record
        return $product->save();

    }

    /**
     * Update a StripeProduct
     * @param $productData
     * @return mixed
     */
    public function _updateProduct($productData) {

        $product = Product::where('id', $productData['id'])->first();

        // Assign Stripe call response object attributes to StripeProduct attributes
        foreach($productData as $attribute => $value) {
            if(PaymentHelper::hasAttribute($product, $attribute)) {
                $product->$attribute = is_array($value) ? json_encode($value) : $value;
            }
        }

        // Save Product record
        return $product->save();

    }

    /**
     * Delete a StripeProduct
     * @param $productData
     * @return mixed
     */
    public function _deleteProduct($productData) {

        $response = null;

        // Delete Product record if it exists and handle any errors.
        $product = Product::where('id', $productData['id'])->first();
        if($product) {
            try {
                $product->delete();
            } catch(QueryException $ex) {
                $response = PaymentHelper::handleEvent($ex->message(), PaymentHelper::loginfo(), 'payment_error', true, false);
            } catch(\Exception $ex) {
                $response = PaymentHelper::handleEvent($ex->message(), PaymentHelper::loginfo(), 'payment_error', true, false);
            }
        } else {
            $response = PaymentHelper::handleEvent(null, null, 'payment_event');
        }

        return $response;

    }

}
