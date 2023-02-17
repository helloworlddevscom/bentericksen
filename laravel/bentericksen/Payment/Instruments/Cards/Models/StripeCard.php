<?php

namespace Bentericksen\Payment\Instruments\Cards\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StripeCard
 * @package Bentericksen\Payment\Methods\Cards\Models
 */
class StripeCard extends Model
{
    /**
     * @var
     */
    protected $primaryKey = "card_id";

}
