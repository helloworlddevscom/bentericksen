<?php

namespace Bentericksen\Payment\Products\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StripeProduct
 * @package Bentericksen\Payment\Products\Models
 */
class StripeProduct extends Model
{

    /**
     * @var
     */
    protected $primaryKey = "prod_id";

    /**
     * Get the StripeProduct's StripePrices
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prices()
    {
        return $this->hasMany('Bentericksen\Payment\Prices\Models\StripePrice', 'prod_id', 'prod_id');
    }

}
