<?php

namespace Bentericksen\Wizard;

use App\ClassificationPolicy;
use App\PolicySetting;

/**
 * Class WizardPolicySave
 * @package Bentericksen\Wizard
 */
class WizardPolicySave
{
    /**
     * @var
     */
    private $businessId;
    /**
     * @var
     */
    private $type;
    /**
     * @var
     */
    private $parameters;

    /**
     * @param $businessId
     * @param $type
     * @param $parameters
     */
    public function __construct($businessId, $type, $parameters)
	{
		$this->businessId = $businessId;
		$this->type = $type;
		$this->parameters = $parameters;
	}

    /**
     *
     */
    public function save()
	{
		//dd($this->parameters);
		if(isset($this->parameters['classification']) && !empty($this->parameters['classification']))
		{
			$this->saveClassificationData($this->parameters['classification']);
			unset( $this->parameters['classification'] );
		}
		
		$this->saveValues( $this->parameters );
	}

    /**
     * @param $parameter
     */
    private function saveValues($parameter)
	{
		$policySetting = PolicySetting::where('business_id', $this->businessId)->where('type', $this->type)->first();
		
		if( is_null( $policySetting ) )
		{
			$policySetting = new PolicySetting;
			$policySetting->business_id = $this->businessId;
			$policySetting->type = $this->type;
		}
		
		$policySetting->settings = json_encode($parameter);
		$policySetting->save();
	}

    /**
     * @param $classifications
     */
    private function saveClassificationData($classifications)
	{
		foreach($classifications AS $key => $classification)
		{
			$classificationType = ClassificationPolicy::where('classification_id', $key)->where('type', $this->type)->first();

			if( is_null($classificationType) )
			{
				$classificationType = new ClassificationPolicy;
				$classificationType->classification_id = $key;
				$classificationType->type = $this->type;
			}
			
			$classificationType->settings = json_encode($classification);			
			$classificationType->save();
		}
	}
}