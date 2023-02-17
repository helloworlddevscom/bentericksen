<?php

namespace Bentericksen\Wizard;

use DB;
use Bentericksen\Wizard\Policy;

/**
 * Class WizardSave
 * @package Bentericksen\Wizard
 */
class WizardSave
{
    /**
     * @var
     */
    private $businessId;

    /**
     * @var array
     */
    private $requiredTypes = ['medical', 'vision', 'dental'];

    /**
     * @var
     */
    private $policies;

    /**
     * @param $businessId
     */
    public function __construct($businessId)
	{
		$this->businessId = $businessId;
		
		//get employees
		$employees = DB::table('users')
						->where('business_id', $this->businessId)
						->get();
								
		$userSettings = DB::table('user_policy_settings')
							->where('business_id', $this->businessId)
							->get();
					
		$this->getWizardSettings();
		$this->setBusinessValues();
		$this->setClassificationValues();		
		
	}

    /**
     *
     */
    private function setBusinessValues()
	{
		$businessSettings = DB::table('business_policy_settings')
								->where('business_id', $this->businessId)
								->first();		
		
		$data = [
			'business_id'				=> $this->businessId,
			'medical_offered'			=> $this->wizardSettings[ 'medical' ]->settings->is_offered,
			'medical_waiting_period'	=> $this->wizardSettings[ 'medical' ]->settings->waiting_period,
			'vision_offered'			=> $this->wizardSettings[ 'vision' ]->settings->is_offered,
			'vision_waiting_period'		=> $this->wizardSettings[ 'vision' ]->settings->waiting_period,
			'dental_offered'			=> $this->wizardSettings[ 'dental' ]->settings->is_offered,
			//'dental_waiting_period'		=> (isset($this->wizardSettings[ 'dental' ]->settings->waiting_period) ? $this->wizardSettings[ 'dental' ]->settings->waiting_period : 0),
			//'dental_type'				=> (isset($this->wizardSettings[ 'dental' ]->settings->benefits_type) ? $this->wizardSettings[ 'dental' ]->settings->benefits_type : ""),
		];
		
		if( is_null( $businessSettings ) )
		{
			DB::table('business_policy_settings')
				->insert($data);
			
		} else {
			DB::table('business_policy_settings')
				->where('business_id', $this->businessId)
				->update($data);
		}

	}

    /**
     *
     */
    private function setClassificationValues()
	{
		foreach($this->wizardSettings['classifications']->settings AS $key => $value )
		{
			$data = [
						'classification_id'							=> $key,
						'medical_same_as_base'						=> (isset($value->medical->same_as_base) ? $value->medical->same_as_base : 0),
						'medical_does_not_receive'					=> (isset($value->medical->does_not_receive) ? $value->medical->does_not_receive : 0),
						'medical_pay_up_to'							=> (isset($value->medical->pay_up_to) ? $value->medical->pay_up_to : null),
						'medical_cap_amount'						=> 0, //(isset($value->medical->cap_amount) ? $value->medical->cap_amount : 0),
						'medical_consideration_pay'					=> (isset($value->medical->consideration_pay) && $value->medical->consideration_pay != "" ? $value->medical->consideration_pay : 0),
						'medical_consideration_pay_amount'			=> (isset($value->medical->consideration_pay_amount) && $value->medical->consideration_pay_amount != "" ? $value->medical->consideration_pay_amount : null),
						'vision_same_as_base'						=> (isset($value->vision->same_as_base) ? $value->vision->same_as_base : 0),
						'vision_does_not_receive'					=> (isset($value->vision->does_not_receive) ? $value->vision->does_not_receive : 0),
						'vision_status'								=> (isset($value->vision->status) ? $value->vision->status : "pending"),
						'vision_pay_up_to'							=> (isset($value->vision->pay_up_to) ? $value->vision->pay_up_to : null),
						'vision_paperwork_completed'				=> (isset($value->vision->paperwork_completed) ? $value->vision->paperwork_completed : 0),
						'dental_same_as_base'						=> (isset($value->dental->same_as_base) ? $value->dental->same_as_base : 0),
						'dental_does_not_receive'					=> (isset($value->dental->does_not_receive) ? $value->dental->does_not_receive : 0),
						'dental_default_pay_up_to'					=> (isset($value->dental->default_pay_up_to) ? $value->dental->default_pay_up_to : null),
						'dental_default_cap_amount'					=> (isset($value->dental->default_cap_amount) ? $value->dental->default_cap_amount : null),
						'dental_discount_percent'					=> (isset($value->dental->discount_percent) ? $value->dental->discount_percent : null),
						'dental_discount_family_benefits'			=> (isset($value->dental->discount_family_benefits) ? $value->dental->discount_family_benefits : 0),
						'dental_discount_family_percent'			=> (isset($value->dental->discount_family_percent) ? $value->dental->discount_family_percent : null),
						'dental_pdba_credit'						=> (isset($value->dental->pdba_credit) ? $value->dental->pdba_credit : null),
						'dental_pdba_cap_amount'					=> (isset($value->dental->pdba_cap_amount) ? $value->dental->pdba_cap_amount : null),
						'dental_pdba_family_benefits'				=> (isset($value->dental->pdba_family_benefits) ? $value->dental->pdba_family_benefits : 0),
						'dental_pdba_family_benefits_type'			=> (isset($value->dental->pdba_family_benefits_type) ? $value->dental->pdba_family_benefits_type : 0),
						'dental_pdba_family_benefits__pdba_percent'	=> (isset($value->dental->pdba_family_benefits__pdba_percent) ? $value->dental->pdba_family_benefits__pdba_percent : null),
			];
			
			//dd($data);
			
			$classification = DB::table('classifications_policy_settings')
								->where('classification_id', $key)
								->first();
			
			$now = new \Carbon\Carbon;
			
			if(is_null( $classification ) )
			{
				
				$data['created_at'] = $now->format('Y-m-d h:i:s');
				
				DB::table('classifications_policy_settings')
					->where('classification_id', $key)
					->insert($data);
			} else {
				
				$data['updated_at'] = $now->format('Y-m-d h:i:s');
				
				DB::table('classifications_policy_settings')
					->where('classification_id', $key)
					->update($data);
			}
		
		}
	}

    /**
     *
     */
    private function getWizardSettings()
	{
		$wizardSettings = DB::table('wizard_settings')
							->where('business_id', $this->businessId)
							->get();

		$temp = [];
		foreach( $wizardSettings AS $settings)
		{
			$temp[ $settings->type ] = $settings;
			$temp[ $settings->type ]->settings = json_decode( $settings->settings );
		}
		
		$this->wizardSettings = $temp;

	}
	
}