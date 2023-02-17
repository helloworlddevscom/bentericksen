<?php

namespace Bentericksen\Employees;

use DB;
use Bentericksen\History\History;

class Benefit
{
	private $userId;
	private $user;
	private $businessId;
	private $classificationIds = [];
	
	private $skipFields = [
		'id',
		'name',
		'is_base',
		'is_enabled',
		'business_id',
		'created_at',
		'updated_at',
		'classification_id',
	];
	
	
	public function __construct( $userId )
	{
		$this->userId = $userId;
		//$this->businessId = $this->getBusinessId();
		$this->user = $this->getUser();
		$this->businessId = $this->user->business_id;
		$this->classification_id = $this->user->classification_id;

		$this->combineData( $this->getBusinessSettings(), $this->getClassifications(), $this->getClassificationSettings(), $this->getUserSettings() );	
		$this->eligibleDates();
	}
	
	public function get($key)
	{
		if( isset( $this->$key ) )
		{
			return $this->$key;
		} else {
			return null;
		}
	}
	
	private function getUser()
	{
		return DB::table('users')
					->where('id', $this->userId)
					->first();
	}

	private function getBusinessSettings()
	{
		return DB::table('business_policy_settings')
					->where('business_id', $this->businessId)
					->first();
	}
	
	private function getClassifications()
	{
		$temp = [];
		
		$results = DB::table('classifications')
					->where('business_id', $this->user->business_id)
					->get();
					
		foreach( $results as $result )
		{
			if( $result->is_base == 1 )
			{
				$this->classificationIds[] = $result->id;
				$temp[ 'base' ] = $result;
			}
			
			if( $result->id == $this->user->classification_id )
			{
				$this->classificationIds[] = $result->id;
				$temp[ 'current' ] = $result;
			}
		}
		
		return $temp;
	}

	private function getClassificationSettings()
	{
		$classifications = [];
		
		$results = DB::table('classifications_policy_settings')
						->whereIn('classification_id', $this->classificationIds)
						->get();
						
		foreach( $results AS $result )
		{
			$classifications[ $result->id ] = $result;
		}

		return $classifications;
	}

	private function getUserSettings()
	{
		return DB::table('user_policy_settings')
					->where('user_id', $this->userId)
					->first();
	}

	private function combineData( $businessSettings, $classifications, $classificationSettings, $userSettings )
	{
		$temp = [];
		
		//setBusinessFields
		if( isset( $businessSettings ) && ! empty( $businessSettings ) )
		{
			foreach( $businessSettings AS $key => $value )
			{
				if( ! in_array( $key, $this->skipFields ) )
				{
					$temp[$key] = $value;
				}
			}
		}
		
		if( isset( $classifications[ 'current' ] ) && ! empty( $classifications[ 'current' ] ) )
		{
			foreach( $classifications[ 'current' ] AS $key => $value )
			{
				if( ! in_array( $key, $this->skipFields ) )
				{
					$temp[$key] = $value;
				}			
			}
		}
		
		/*
		if( isset( $classifications[ 'current' ] ) && ! empty( $classifications[ 'current' ] ) )
		{
			foreach( $classificationSettings[ $classifications[ 'current' ]->id ] AS $key => $value )
			{
				if( ! in_array( $key, $this->skipFields ) )
				{
					$temp[$key] = $value;
				}			
			}
		}
		*/
		
		if( ! is_null( $userSettings ) )
		{
			foreach( $userSettings AS $key => $value )
			{
				dd($value);
			}
		}
		
		foreach( $temp AS $key => $value )
		{
			$this->$key = $value;
		}
		
	}

	private function eligibleDates()
	{
		$now = new \Carbon\Carbon;
		foreach( ['vision', 'medical', 'dental'] AS $type )
		{
			$variable = $type . "_waiting_period";
			if( isset( $this->$variable) && ! is_null( $this->$variable ) )
			{
				if( isset( $this->user->hired ) && ! is_null( $this->user->hired ))
				{
					$eligible = \Carbon\Carbon::createFromFormat( 'Y-m-d h:i:s', $this->user->hired )->addDays( $this->$variable );
					$this->{$type . "_eligible"} = $eligible->format( 'm/d/Y' );
					if($now->timestamp > $eligible->timestamp)
					{
						$this->{$type . "_status"} = "enrolled";
					}					
				} else {
					$this->{$type . "_eligible"} = "";
				}
				
			
			}

		}
	}
	
}