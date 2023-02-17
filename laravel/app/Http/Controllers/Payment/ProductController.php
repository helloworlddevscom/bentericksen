<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Facades\PaymentService;

/**
 * Class ProductController
 * @package App\Http\Controllers\Payment
 */
class ProductController extends Controller
{

    /**
     * Get all products
     * @return mixed
     */
    public function index()
    {

        return PaymentService::productComponent()->getAllProducts();

    }

    /**
     * Create a product
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function store(Request $request, Response $response)
    {

        return PaymentService::productComponent()->createProduct($request->data, $response);

    }

    /**
     * Get a product
     * @param $product_id
     * @return mixed
     */
    public function show($product)
    {

        return PaymentService::productComponent()->getProduct($product);

    }

    /**
     * Update a product
     * @param $product_id
     * @param Request $request
     * @param Response $response
     */
    public function update($product, Request $request, Response $response)
    {

        return PaymentService::productComponent()
                ->updateProduct(
                    $product,
                    $request->data,
                    $response
                );

    }

    /**
     * Delete a product
     * @param $product_id
     * @param Response $response
     * @return mixed
     */
    public function destroy($product, Response $response)
    {

        return PaymentService::productComponent()->deleteProduct($product, $response);

    }

}
