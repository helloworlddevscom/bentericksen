<?php

namespace Bentericksen\Policy;

use App\Business;
use Carbon\Carbon;

/**
 * Class PolicyRules
 *
 * Contains some business logic for determining if a Policy Updater applies to
 * a given business.
 *
 * @package Bentericksen\Policy
 *
 * Several methods on this class are called using PHP's variable method calls,
 * which are undetectable by PHP Mess Detector. Suppress a PHPMD error.
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
 */
class PolicyRules
{
    /**
     * @var Business
     */
    protected $business;

    /**
     * @var array
     */
    protected $checks = [
        'State',
        'EmployeeCount',
        'BusinessType',
    ];


    /**
     * PolicyRules constructor.
     *
     * @param \App\Business $business
     */
    public function __construct($business)
    {
        $this->business = $business;
    }

    /**
     * Run all the checks listed in array
     *
     * @param \App\PolicyTemplate $policyTemplate
     *
     * @return bool
     */
    public function all($policyTemplate)
    {
        foreach ($this->checks as $check) {
            $method = "check{$check}";
            if ($this->$method($policyTemplate) !== true) {
                return false;
            }
        }
        return true;
    }

    /**
     * Checks if policy applies for Business State.
     *
     * @param \App\PolicyTemplate $policyTemplate
     *
     * @return bool
     */
    private function checkState($policyTemplate)
    {
        $states = json_decode($policyTemplate->state);

        if (is_null($states)) {
            $states = [];
        }

        if (in_array("ALL", $states)) {
            return true;
        }

        if (in_array("Non-MT", $states) && $this->business->state !== "MT") {
            return true;
        }

        if (in_array($this->business->state, $states)) {
            return true;
        }

        return false;
    }


    /**
     * Check if business number of employees is compatible with policy
     *
     * @param \App\PolicyTemplate $policyTemplate
     *
     * @return bool
     */
    private function checkEmployeeCount($policyTemplate)
    {
        if (is_null($policyTemplate->min_employee) && is_null($policyTemplate->max_employee)) {
            return true;
        }

        if (is_null($policyTemplate->min_employee) && $this->business->additional_employees <= $policyTemplate->max_employee) {
            return true;
        }

        if ($this->business->additional_employees >= $policyTemplate->min_employee && $this->business->additional_employees <= $policyTemplate->max_employee) {
            return true;
        }

        return false;
    }


    /**
     * Checks if effective date of policy is in the future.
     *
     * @param \App\PolicyTemplate $policyTemplate
     *
     * @return bool
     */
    private function checkEffectiveDate($policyTemplate)
    {
        $today = Carbon::today();
        return strtotime($today) >= strtotime($policyTemplate->effective_date);
    }


    /**
     * Checks if business type is compatible with policy requirements.
     *
     * @param \App\PolicyTemplate $policyTemplate
     *
     * @return bool
     */
    private function checkBusinessType($policyTemplate)
    {
        $requirements = $policyTemplate->requirement;

        if (in_array('required', $requirements) or in_array('optional', $requirements)) {
            return true;
        }

        if ($this->business->type === "dental") {
            if (in_array('drequired', $requirements) or in_array('doptional', $requirements)) {
                return true;
            }
        }

        if ($this->business->type === "commercial") {
            if (in_array('crequired', $requirements) or in_array('coptional', $requirements)) {
                return true;
            }
        }

        if ($this->business->type === "medical") {
            if (in_array('mrequired', $requirements) or in_array('moptional', $requirements)) {
                return true;
            }
        }

        if ($this->business->type === "veterinarian") {
            if (in_array('vrequired', $requirements) or in_array('voptional', $requirements)) {
                return true;
            }
        }

        return false;
    }
}
