<?php

namespace Bentericksen\Employees\Benefits;

use DB;
use Bentericksen\History\History;

class Benefits
{
	private $user;
	private $business;
	private $settings = [];
	
	public function __construct( $userId )
	{
		$this->user = $this->getUser( $userId );
		$this->business = $this->getBusiness( $this->user->business_id );
		
		$this->getBusinessSettings();
		$this->getClassificationSettings();
		
		$this->eligibleDates();
		
	}
	
	public function get($key)
	{
		if( isset( $this->settings[ $key ] ) )
		{
			return $this->settings[ $key ];
		} else {
			return null;
		}
	}
	
	private function getUser( $userId )
	{
		return DB::table( 'users' )
					->where( 'id', $userId )
					->first();
	}
	
	private function getBusiness( $businessId )
	{
		return DB::table( 'business' )
					->where( 'id', $businessId )
					->first();		
	}

	private function getBusinessSettings()
	{
		return;
		
		$results = DB::table('business_policy_settings')
					->where('business_id', $this->business->id)
					->first();
					
		$results = json_decode( json_encode( $results ), true );
		
		//if no settings( this should only happen if they haven't went through the wizards)
		if(is_null( $results ) )
		{
			return;
		}
		
		unset( $results['created_at'] );
		unset( $results['updated_at'] );
		
		
		
		foreach( $results as $key => $value )
		{
			$this->settings[ $key ] = $value;
		}
		
	}

	private function getClassificationSettings()
	{	
		$results = DB::table('classifications_policy_settings')
						->where( 'classification_id', $this->user->classification_id )
						->first();
		
		if( ! is_null( $results ) )
		{		
			$results = json_decode( json_encode( $results ), true );
			unset( $results['created_at'] );
			unset( $results['updated_at'] );
			
			foreach( $results as $key => $value )
			{
				if( ! is_null( $value ) )
				{
					$this->settings[ $key ] = json_decode( $value );
				}
			}
		}

	}

	private function getUserSettings()
	{
		return DB::table('user_policy_settings')
					->where('user_id', $this->user->id )
					->first();
	}

	private function eligibleDates()
	{
		$now = new \Carbon\Carbon;
		foreach( ['vision', 'medical', 'dental'] AS $type )
		{
			$variable = $type . "_waiting_period";
			if( isset( $this->settings[ $variable ] ) && ! is_null( $this->settings[ $variable ] ) )
			{
				if( isset( $this->user->hired ) && ! is_null( $this->user->hired ) && $this->user->hired != "")
				{
					if( ! isset($this->settings[ $type ] ) )
					{
						$this->settings[ $type ] = new \StdClass;
					}
					
					$eligible = \Carbon\Carbon::createFromFormat( 'Y-m-d h:i:s', $this->user->hired )->addDays( $this->settings[ $variable ] );
					
					$this->settings[ $type ]->eligible = $eligible->format( 'm/d/Y' );
					
					if($now->timestamp > $eligible->timestamp)
					{
						$this->settings[ $type ]->status = "enrolled";
					}					
				} else {
					//$this->settings[ $type ]->eligible = "";
				}
				
			
			}

		}
	}
	
	
}