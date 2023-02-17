<?php

namespace Bentericksen\Wizard;

use DB;

class WizardSaveNew
{
	private $business;
	private $settings;
	
	private $businessSettings;
	
	public function __construct( $business, $settings )
	{
		$this->business = $business;
		$this->settings = $settings;
	
		//these methods should probably call their own classes
		$this->setBusinessSettings();
		$this->setClassificationSettings();
		
	}
	
	private function setBusinessSettings()
	{
	
		$businessSettings = DB::table('business_policy_settings')
								->where('business_id', $this->business->id)
								->first();
								
		$data = [
			'business_id'				=> $this->business->id,
			'medical_offered'			=> (isset($this->settings->medical->is_offered) ? $this->settings->medical->is_offered : 0),
			'medical_waiting_period'	=> (isset($this->settings->medical->waiting_period) ? $this->settings->medical->waiting_period : 0),
			'vision_offered'			=> (isset($this->settings->vision->is_offered) ? $this->settings->vision->is_offered : 0),
			'vision_waiting_period'		=> (isset($this->settings->vision->waiting_period) ? $this->settings->vision->waiting_period : 0),
			'dental_offered'			=> (isset($this->settings->dental->is_offered) ? $this->settings->dental->is_offered : 0),
			'dental_waiting_period'		=> (isset($this->settings->dental->waiting_period) ? $this->settings->dental->waiting_period : 0),
			'dental_type'				=> (isset($this->settings->dental->benefits_type) ? $this->settings->dental->benefits_type : ""),
			'dental_offered'			=> (isset($this->settings->dental->is_offered) ? $this->settings->dental->is_offered : 0),
			'holiday_offered'			=> (isset($this->settings->holidays->is_offered) ? $this->settings->holidays->is_offered : 0),
			'sickleave_offered'			=> (isset($this->settings->sickleave->is_offered) ? $this->settings->sickleave->is_offered : 0),
			'vacation_pto_offered'		=> (isset($this->settings->vacation_pto->is_offered) ? $this->settings->vacation_pto->is_offered : 0),
			'bereavement_leave_offered'	=> (isset($this->settings->bereavement_leave->is_offered) ? $this->settings->bereavement_leave->is_offered : 0),
			'severance_offered'			=> (isset($this->settings->severance->is_offered) ? $this->settings->severance->is_offered : 0),
			'referral_offered'			=> (isset($this->settings->referral->is_offered) ? $this->settings->referral->is_offered : 0),
			'reference_offered'			=> (isset($this->settings->reference->is_offered) ? $this->settings->reference->is_offered : 0),
			'harassment_offered'		=> (isset($this->settings->harassment->is_offered) ? $this->settings->harassment->is_offered : 0),
			'resolution_offered'		=> (isset($this->settings->resolution->is_offered) ? $this->settings->resolution->is_offered : 0),
			'petcare_offered'			=> (isset($this->settings->petcare->is_offered) ? $this->settings->petcare->is_offered : 0),
		];
		
		if( is_null( $businessSettings ) )
		{
			$data['created_at'] = (new \Carbon\Carbon)->format('Y-m-d h:i:s');
			DB::table('business_policy_settings')
				->insert($data);
			
		} else {
			$data['updated_at'] = (new \Carbon\Carbon)->format('Y-m-d h:i:s');
			DB::table('business_policy_settings')
				->where('business_id', $this->business->id)
				->update($data);
		}
	}
	
	private function setClassificationSettings()
	{
		$benefit_types = ['medical', 'vision', 'dental', 'vacation_pto', 'holidays', 'sickleave', 'bereavement_leave', 'petcare','referral', 'resolution', 'harassment'];
		
		$data = [];
		
		foreach($this->settings->classifications AS $key => $classification )
		{
			$data[ 'business_id' ] = $this->business->id;
			$data[ 'classification_id' ] = $key;
			
			foreach( $benefit_types AS $type )
			{
				if( isset( $classification->{$type} ) )
				{
					$data[ $type ] = json_encode( $classification->{$type} );
				}
			}
			
			$classificationData = DB::table('classifications_policy_settings')
										->where('business_id', $this->business->id )
										->where( 'classification_id', $key )
										->first();
										
			if( ! is_null( $classificationData ) )
			{
				//update
				$data['updated_at'] = (new \Carbon\Carbon)->format('Y-m-d h:i:s');
				DB::table( 'classifications_policy_settings' )
					->where( 'business_id', $this->business->id )
					->where( 'classification_id', $key )
					->update( $data );
			} else {
				//insert
				$data['created_at'] = (new \Carbon\Carbon)->format('Y-m-d h:i:s');
				DB::table( 'classifications_policy_settings' )
					->insert( $data );				
			}
		}
	}
	
}