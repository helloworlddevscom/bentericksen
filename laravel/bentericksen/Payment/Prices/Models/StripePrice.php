<?php

namespace Bentericksen\Payment\Prices\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StripePrice
 * @package Bentericksen\Payment
 */
class StripePrice extends Model
{

    /**
     * @var
     */
    protected $primaryKey = "price_id";

    /**
     * Get the StripePrices' StripeProduct
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {

        return $this->belongsTo('Bentericksen\Payment\Products\Models\StripeProduct', 'prod_id', 'prod_id');

    }

}
