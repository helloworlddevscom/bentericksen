<?php

namespace Bentericksen\Policy;

use App\Policy;

class PolicySpecialRules
{

    const MEDICAL = 269;

    const SICKLEAVE = 280;

    const PTO = 281;

    const VACATION = 282;

    const FLEXMD = 406;

    protected $business;

    public function setBusiness($business)
    {
        $this->business = $business;
    }

    public function check($policy)
    {
        if ($this->business->state === "HI") {
            return $this->HI($policy);
        }

        if ($this->business->state === "MD") {
            return $this->MD($policy);
        }
    }

    private function HI($policy)
    {
        if ($policy->template_id === self::MEDICAL) {
            $special = Policy::where('business_id', $this->business->id)
                ->where('template_id', 416)->first();

            if ($policy->status === "enabled") {
                $special->status = "enabled";
            } else {
                $special->status = "closed";
            }
            $special->save();

            return "reload";
        }
    }

    private function MD($policy)
    {
        if ($this->business->additional_employees < 15) {
            return;
        }

        if ($policy->template_id === self::PTO || $policy->template_id === self::VACATION || $policy->template_id === self::SICKLEAVE) {
            $special = Policy::where('business_id', $this->business->id)
                ->where('template_id', self::FLEXMD)->first();

            $sick = Policy::where('business_id', $this->business->id)
                ->where('template_id', self::SICKLEAVE)
                ->first();
            $vacation = Policy::where('business_id', $this->business->id)
                ->where('template_id', self::VACATION)
                ->whereNull('special_extra')
                ->first();
            $pto = Policy::where('business_id', $this->business->id)
                ->where('template_id', self::PTO)
                ->whereNull('special_extra')
                ->first();

            $special->status = "closed";
            if (!is_null($sick) && $sick->status === "enabled") {
                $special->status = "enabled";
            }

            if (!is_null($vacation) && $vacation->status === "enabled") {
                $special->status = "enabled";
            }

            if (!is_null($pto) && $pto->status === "enabled") {
                $special->status = "enabled";
            }

            $special->save();

            if ($special->status == "enabled" or $policy->status == "disabled") {
                return "reload";
            }
        }
    }


}
