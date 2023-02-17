<?php
//Remove if not errors in the next 2 days 2015-08-10

namespace Bentericksen\Wizard;

use DB;

/**
 * Class WizardUpdate
 * @package Bentericksen\Wizard
 */
class WizardUpdate
{
    /**
     * @var
     */
    private $businessId;
	
    /**
     * @var array
     */
    private $fillable = [ 'classifications', 'medical', 'vision', 'dental', 'sickleave' ];

    /**
     * @param $businessId
     */
    public function __construct( $businessId )
	{
		$this->businessId = $businessId;
	}

    /**
     * @param $parameters
     */
    public function update( $parameters )
	{
		foreach( $parameters AS $key => $parameter )
		{
			//This is for modifying the business assigned classifications which are separate from the wizard values. This should be moved out of this in the future.
			if($key == "classification")
			{
				$this->updateClassification( $parameter );
				continue;
			}
			
			//set and update wizard values
			if( in_array( $key, $this->fillable ) )
			{
				$this->setValues( $key, $parameter );
			}
		}		
	}

    /**
     * @param $key
     * @param $parameters
     */
    private function setValues( $key, $parameters )
	{
		$value = DB::table('wizard_settings')
					->where('business_id', $this->businessId)
					->where('type', $key)
					->first();
		
		if( is_null( $value ) )
		{
			DB::table('wizard_settings')
				->insert([
					'business_id'	=> $this->businessId,
					'type'			=> $key,
					'settings'		=> json_encode( $this->combineParameters( [], $parameters  ) ),
				]);
				
		} else {
			DB::table('wizard_settings')
				->where('business_id', $this->businessId)
				->where('type', $key)
				->update([
					'settings' => json_encode( $this->combineParameters( json_decode( $value->settings ), $parameters ) ) 
				]);
		}
		
	}

    /**
     * @param $currentParameters
     * @param $newParameters
     * @return mixed
     */
    private function combineParameters( $currentParameters, $newParameters )
	{
		$updatedParameters = json_decode(json_encode($currentParameters) ,true);
		
		foreach( $newParameters AS $key => $parameter )
		{
			if(is_array($parameter))
			{
				foreach( $parameter AS $k => $value )
				{
					$updatedParameters[$key][$k] = $value;	
				}
			} else {
				$updatedParameters[$key] = $parameter;
			}
		}
				
		return $updatedParameters;
	}


    /**
     * @param $parameter
     */
    private function updateClassification( $parameter )
	{
		if( isset( $parameter['new'] ) )
		{
			foreach($parameter['new'] AS $new)
			{
				$new['business_id'] = $this->businessId;
				DB::table('classifications')
					->insert($new);
					
			}
			unset( $parameter['new'] );
		}

	
		foreach( $parameter AS $key => $value )
		{
			$classification = DB::table('classifications')
									->where('business_id', $this->businessId)
									->where('id', $key)
									->first();
			DB::table('classifications')
				->where('business_id', $this->businessId)
				->where('id', $key)
				->update($value);
			
		}
		
	}
	
}