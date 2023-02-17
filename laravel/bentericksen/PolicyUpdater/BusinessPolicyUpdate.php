<?php

namespace Bentericksen\PolicyUpdater;

use App\Business;

class BusinessPolicyUpdate extends PolicyUpdateAbstract
{

    /**
     * @var Business
     */
    public $business;

    public function __construct($business_id)
    {
        $this->business = Business::findOrFail($business_id);
    }
}
