<?php

namespace Bentericksen\Payment\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StripeSubscription
 * @package Bentericksen\Payment\Subscriptions\Models
 */
class StripeSubscription extends Model
{

    /**
     * @var
     */
    protected $primaryKey = "sub_id";
    protected $table = "stripe_subscriptions";

    /**
     * Get the StripeSubscription's Business instance
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo('App\Business');
    }
}
