<?php

namespace Bentericksen\Wizard\Settings;

use DB;

class SettingsLoad
{
	
	private $businessId;
	
	private $settings = [];
	
	public function __construct( $businessId )
	{
		$this->businessId = $businessId;
	}
	
	
	public function load()
	{
		$this->loadBusiness();
		$this->loadClassifications();
		$this->loadClassificationsBenefits();

		return $this->arrayToObject( $this->settings );
	}
	
	private function loadBusiness()
	{
		$business = DB::table( 'business' )->where( 'id', $this->businessId )->first();
				
		$results = DB::table( 'wizard_business' )
						->where( 'business_id', $business->id )
						->first();
		
		$this->set( 'business_type', $business->type );
		
		if(count($results) > 0)
		{		
			$settings = json_decode( $results->settings );
			
			$this->set( 'employee_count', $results->employee_count );
			$this->set( 'state', $results->state );
					
			$settings = json_decode( $results->settings, true);
			
			foreach( $settings AS $key => $value )
			{
				$this->set( $key, $value );
			}
		} else {
			$employees = DB::table( 'users' )
							->where( 'business_id', $business->id )
							->get();
							
			$employee_count = count($employees) + $business->additional_employees;
			
			$this->set( 'employee_count', $employee_count );
			$this->set( 'state', $business->state );			
		}
	}
	
	private function loadClassifications()
	{
		$skip = ['created_at', 'updated_at', 'business_id'];
		
		$results = DB::table( 'classifications' )
					->where( 'business_id', $this->businessId )
					->get();
		
		$temp = [];
		
		foreach($results AS $result)
		{
			foreach( $result AS $key => $value )
			{
				if( ! in_array( $key, $skip ) )
				{
					$temp[ $result->id ][ $key ] = $value;
				}
			}
		}
		
		$this->set( 'classifications', $temp);
	}
	
	
	//need to re-abtract this add so that it uses a single entry point vs using its own set for classification values
	private function loadClassificationsBenefits()
	{
		$results = DB::table('wizard_classifications')
						->where('business_id', $this->businessId)
						->get();
			
		
		foreach( $results as $result )
		{
			$value = json_decode( $result->settings, true );
			$this->settings['classifications'][ $result->classification_id ][ $result->benefit_type ] = $value;
		}
	}	
	
	private function set( $key, $value )
	{
		$this->settings[ $key ] = $value;
	}
		
	
	private function arrayToObject( $array )
	{
		$obj = new \stdClass;
		foreach( $array as $key => $value ) 
		{
			if( strlen( $key ) ) 
			{
				if( is_array( $value ) ) 
				{
					$obj->{$key} = $this->arrayToObject($value);
				} else {
					$obj->{$key} = $value;
				}
			}
		}
		
		return $obj;
	
	}	
	
}