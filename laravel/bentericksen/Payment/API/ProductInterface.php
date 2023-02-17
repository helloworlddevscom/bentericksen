<?php


namespace Bentericksen\Payment\API;

/**
 * Interface ProductInterface
 * @package Bentericksen\Payment\API
 */
interface ProductInterface
{

    /**
     * Create a product
     * @param $params
     * @return mixed
     */
    public function createProduct($productData, $response);

    /**
     * Update a product
     * @param $product
     * @param $productData
     * @return mixed
     */
    public function updateProduct($product, $metaData, $response);

    /**
     * Get a product
     * @param $product
     * @return mixed
     */
    public function getProduct($product);

    /**
     * Get all products
     * @param $params
     * @return mixed
     */
    public function getAllProducts();

    /**
     * Delete a product
     * @param $product
     * @return mixed
     */
    public function deleteProduct($product, $response);

}
