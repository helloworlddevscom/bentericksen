<?php

namespace Bentericksen\Permissions;

use App\Business;
use App\BusinessPermission;

class NewBusinessPermissionsSetup
{
    /**
     * @var Business
     */
    private $business;
    /**
     * @var BusinessPermission
     */
    private $businessPermission;

    /**
     * NewBusinessPermissionsSetup constructor.
     * @param Business $business
     */
    public function __construct(Business $business)
    {
        $this->business = $business;
        $this->businessPermission = new BusinessPermission;
    }


    public function init()
    {
        $array = array_fill_keys(array_flip($this->businessPermission->getColumns()), 1);

        $array['business_id'] = $this->business->id;

        return $this->businessPermission->create($array);
    }
}
