<?php

namespace Bentericksen\Wizard\Classifications;

use DB;

class Classifications
{
	protected $business_id;
	
	public function __construct( $business_id )
	{
		$this->business_id = $business_id;
	}
	
	public function save( $data )
	{
		foreach( $data AS $key => $value )
		{
			foreach( $value AS $k => $val )
			{
				$wizardSettings = DB::table( 'wizard_classifications' )
										->where( 'business_id', $this->business_id )
										->where( 'classification_id', $val[ 'classification_id' ] )
										->where( 'benefit_type', $val[ 'benefit_type' ] )
										->first();
										
				if( ! is_null( $wizardSettings ) )
				{
					DB::table( 'wizard_classifications' )
						->where( 'business_id', $this->business_id )
						->where( 'classification_id', $val[ 'classification_id' ] )
						->where( 'benefit_type', $val[ 'benefit_type' ] )
						->update( $val );	
						
				} else {
					DB::table( 'wizard_classifications' )
						->insert( $val );
				}
			}			
		}
	}

	public function createNew( $data )
	{
		$temp = [];
		
		foreach( $data AS $key => $value )
		{
			$fields = [
				'business_id'				=> $this->business_id,
				'name'						=> $value[ 'name' ],
				'is_base'					=> 0,
				'is_enabled'				=> $value[ 'is_enabled' ],
				'minimum_hours'				=> 0,
				'minimum_hours_interval'	=> "day",
				'maximum_hours'				=> 1,
				'maximum_hours_interval'	=> "day",
				'created_at'				=> (new \Carbon\Carbon)->format('Y-m-d h:i:s'),
			];
		
			$classification = DB::table('classifications')
								->insertGetId( $fields );		
								
			$fields[ 'id' ] = $classification;
			
			$temp[] = [
				"id"	=> $classification,
				"data"	=> json_decode( json_encode( $fields ) )
			];
			
		}	
		
		return $temp;
	}
}