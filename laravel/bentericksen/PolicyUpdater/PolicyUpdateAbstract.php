<?php

namespace bentericksen\PolicyUpdater;

use App\Policy;
use App\PolicyTemplateUpdate;
use DB;
use Carbon\Carbon;

abstract class PolicyUpdateAbstract
{
    const PLACEHOLDER_STRING = '<h1>If you see this, you need still need to select between multiple policy types</h1>';
    
    protected $updated_policies = [];

    /**
     * Set policy updates
     * @return array
     */
    public function setUpdatedPolicies()
    {   
        return $this->getUpdatedPolicies();
    }

    public function getUpdatedPolicies()
    {
        return $this->updated_policies ?? [];
    }

    /**
     * Gets the Policy Updates that need to be accepted by the Business
     * @return PolicyTemplateUpdate[]|bool
     */
    public function getPolicyTemplateUpdates()
    {
        $new_policies = [];

        if (empty($this->updated_policies)) {
            return false;
        }

        foreach ($this->updated_policies as $updated_policy) {
            $new_policies[] = PolicyTemplateUpdate::find($updated_policy->policy_template_update_id);
        }

        return $new_policies;
    }

    public function getUpdate()
    {
        return $this->updated_policies;
    }

    /**
     * Gets the associated business id
     * @return int
     */
    public function getBusinessId()
    {
        return $this->business->id;
    }

    /**
     * Gets the old versions of policies that need to be accepted
     * @return array
     */
    public function getCurrentPolicies($new_policies = [])
    {
        $policies = [];

        if (empty($this->getUpdatedPolicies())) {
            return $policies;
        }
        
        foreach ($this->getUpdatedPolicies() as $updated_policy) {

            $template_update = array_filter($new_policies, function($update) use($updated_policy) {
                return $update->id == $updated_policy->policy_template_update_id;
            });

            if (empty($template_update)) {
                continue;
            }

            $template_update = current($template_update);
            
            $policies[$updated_policy->policies] = Policy::where('business_id', $this->getBusinessId())
                ->where('template_id', $updated_policy->policies)
                ->orWhere('business_id', $this->getBusinessId())
                ->where('manual_name', $template_update->manual_name)
                ->first();

            if (!is_null($policies[$updated_policy->policies]) && $policies[$updated_policy->policies]->content == self::PLACEHOLDER_STRING) {
                $policies[$updated_policy->policies]->content = $policies[$updated_policy->policies]->content_raw;
            }
        }

        return $policies;
    }

    /**
     * Accept policy updates
     * @return void
     */
    public function acceptUpdates($id) {
    }
}
