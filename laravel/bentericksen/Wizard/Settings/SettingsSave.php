<?php

namespace Bentericksen\Wizard\Settings;

use DB;
use Bentericksen\Wizard\Classifications\Classifications;

class SettingsSave
{
	private $businessId;
	
	private $settings = [];
	
	private $classifications;
	
	private $classificationsSettings;
	
	private $classificationsBenefitSettings;
	
	private $businessSettings;
	
	private $temp = [];
	
	public function __construct( $businessId )
	{
		$this->businessId = $businessId;
	}
	
	public function set( $data )
	{
		$this->settings = $data;
		//dd( $this->settings );
	}
	
	public function save()
	{
		$this->setBusinessSettings();
		$this->setClassificationSettings();
		
		$this->store();
	}
	
	private function store()
	{
		//store business;
		$data['business_id'] = $this->businessId;
		unset($this->businessSettings['business_id']);
		
		$data['state'] = $this->businessSettings['state'];
		unset($this->businessSettings['state']);			
		
		$data['employee_count'] = $this->businessSettings['employee_count'];
		unset($this->businessSettings['employee_count']);

		$data['settings'] = json_encode( $this->businessSettings );		
		$wizardBusiness = DB::table('wizard_business')->where('business_id', $this->businessId)->first();
		if( is_null( $wizardBusiness ) )
		{
			$data['created_at'] = (new \Carbon\Carbon)->format('Y-m-d h:i:s');
			DB::table('wizard_business')->insert( $data );
		} else {
			$data['updated_at'] = (new \Carbon\Carbon)->format('Y-m-d h:i:s');
			DB::table('wizard_business')
				->where('business_id', $this->businessId)
				->update( $data );
		}
		
		//store classifications;
		if( ! is_null( $this->classifications ) )
		{
			foreach($this->classifications as $classification)
			{
				$data = json_decode( json_encode( $classification ), true );
				$id = $data['id'];
				unset( $data['id'] );

				$wizardClassification = DB::table('classifications')->where('id', $id)->first();
				if( ! is_null( $wizardClassification ) )
				{
					$data['updated_at'] = (new \Carbon\Carbon)->format('Y-m-d h:i:s');
					DB::table('classifications')
						->where('id', $id)
						->update( $data );
				} 
			}
		}
		
		if( ! is_null( $this->classificationsBenefitSettings ) )
		{
			$classification = new Classifications( $this->businessId );
			$classification->save( $this->classificationsBenefitSettings );
		}
		
	}
	
	private function setBusinessSettings()
	{
		foreach( $this->settings AS $key => $value )
		{
			if( $key == "classifications" ) continue;
			
			$this->businessSettings[ $key ] = $value;
		}
	}
	
	private function setClassificationSettings()
	{
		$classifications = json_decode( json_encode( $this->settings->classifications ), true );
		
		foreach( $classifications AS $topKey => $classification )
		{
			foreach($classification as $key => $value)
			{
				
				if(is_array($value) )
				{					
					$this->setClassificationBusinessSettings( $classification[ 'id' ], $key, $value );
				} else {
					$this->classifications[ $classification['id'] ][ $key ] = $value;
				}
			}
		}
	}
	
	private function setClassificationBusinessSettings( $classification_id, $benefit_type, $data )
	{
		$fields = [
			'business_id'		=> $this->businessId,
			'classification_id'	=> $classification_id,
			'benefit_type'		=> $benefit_type,
			'settings'			=> json_encode( $data ),
		];
		

		
		$this->classificationsBenefitSettings[ $classification_id ][] = $fields;
	}
	
}