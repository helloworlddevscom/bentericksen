<?php

namespace Bentericksen\Wizard;

use DB;

/**
 * Class WizardParameters
 * @package Bentericksen\Wizard
 */
class WizardParameters
{
    /**
     * @var
     */
    private $businessId;
    /**
     * @var
     */
    public $business_type;

    /**
     * @var array
     */
    private $types = [ 'classifications', 'medical', 'vision', 'dental', 'sickleave' ];

    /**
     * @var
     */
    public $parameters;

    /**
     * @var
     */
    public $classifications;

    /**
     * @param $businessId
     */
    public function __construct( $businessId )
	{
		$this->businessId = $businessId;
		$this->business_type = DB::table('business')->where('id', $businessId)->pluck('type');

		$this->getWizardSettings();
		$this->getClassifications();
		
	}

    /**
     *
     */
    public function getClassifications()
	{
		$this->classifications = DB::table( 'classifications' )
									->where( 'business_id', $this->businessId )
									->orderBy( 'is_base', 'desc' )
									->orderBy( 'name', 'asc' )
									->get();
		
		if( isset( $this->parameters['classifications'] ) && !empty( $this->parameters['classifications'] ) )
		{
			foreach( $this->classifications AS $key => $classification )
			{
				foreach( $this->parameters['classifications']->{$classification->id} AS $k => $value )
				{
					$this->classifications[$key]->$k = $value;
					
				}
			}
		}
	}


    /**
     *
     */
    private function getWizardSettings()
	{
		$temp = DB::table( 'wizard_settings' )
			->whereIn( 'type', $this->types )
			->where( 'business_id', $this->businessId )
			->get();
		
		foreach($temp AS $tmp)
		{
			$this->parameters[ $tmp->type ] = json_decode( $tmp->settings );
		}
	}
	
}