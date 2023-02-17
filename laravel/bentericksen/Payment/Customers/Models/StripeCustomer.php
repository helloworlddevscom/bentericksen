<?php

namespace Bentericksen\Payment\Customers\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StripeCustomer
 * @package Bentericksen\Payment\Customers\Models
 */
class StripeCustomer extends Model
{

    /**
     * @vars
     */
    protected $table = "stripe_customers";
    protected $primaryKey = "cus_id";

    /**
     * Get the Customer's User instance
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
