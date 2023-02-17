<?php

namespace Bentericksen\Employees;

use DB;
use App\User AS UserModel;

class Benefits
{
	private $userId;

	private $pto_vacation;
	private $medical;
	private $dental;
	private $user;
	
	public function __construct($userId)
	{
		$this->userId = $userId;
		$this->user = UserModel::find( $userId );
				

		$this->mergePolicies();
		
	}
	
	//

	public function getMedicalOffered()
	{
		return $this->medical_offered;
	}
	
	public function getMedicalWaitingPeriod()
	{
		return $this->medical_waiting_period;
	}
		
	public function getMedicalPayUpTo()
	{
		return $this->medical_pay_up_to;
	}
		
	public function getVisionOffered()
	{
		return $this->vision_offered;
	}
	
	public function getVisionWaitingPeriod()
	{
		return $this->vision_waiting_period;
	}
	
	public function getVisionPayUpTo()
	{
		return $this->vision_pay_up_to;
	}	

	public function getDentalOffered()
	{
		return $this->dental_offered;
	}
	
	public function getDentalWaitingPeriod()
	{
		return $this->dental_waiting_period;
	}	
	
	public function getDentalType()
	{
		if(isset($this->dental_type) && !is_null($this->dental_type))
		{
			return $this->dental_type;
		} else {
			return "";
		}
	}
	
	
	/*
		"medical_same_as_base" => 0
		"medical_does_not_receive" => 0
		"medical_pay_up_to" => 12
		"medical_cap_amount" => 5
		"medical_consideration_pay" => 1
		"medical_consideration_pay_amount" => null
		"vision_same_as_base" => 0
		"vision_does_not_receive" => 0
		"vision_status" => "pending"
		"vision_pay_up_to" => 0
		"vision_paperwork_completed" => 0
		"dental_same_as_base" => 0
		"dental_does_not_receive" => 0
		"dental_default_pay_up_to" => null
		"dental_default_cap_amount" => null
		"dental_discount_percent" => null
		"dental_discount_family_benefits" => 0
		"dental_discount_family_percent" => null
		"dental_pdba_credit" => null
		"dental_pdba_cap_amount" => null
		"dental_pdba_family_benefits" => 0
		"dental_pdba_family_benefits_type" => 0
		"dental_pdba_family_benefits__pdba_percent" => null	
	*/
	
	//
	public function getPtoVacation()
	{
		return $this->pto_vacation;
	}
	
	public function getMedical()
	{
		return $this->medical;
	}
	
	private function mergePolicies()
	{
		$business = DB::table( 'business_policy_settings' )
						->where( 'business_id', $this->user->business_id )
						->first();
		
		$classification = DB::table('classifications_policy_settings')
								->where( 'classification_id', $this->user->classification_id )
								->first();
								
		$user = DB::table('user_policy_settings')
							->where( 'user_id', $this->userId )
							->first();	

		$temp = [];
		
		if( !is_null( $business ) )
		{		
			foreach( $business AS $key => $value )
			{
				if($key == "id" OR $key == "business_id" OR $key == "created_at" OR $key == "updated_at") continue;
				$temp[$key] = $value;
			}
		}

		if( !is_null( $classification ) )
		{
			foreach( $classification AS $key => $value )
			{
				if($key == "id" OR $key == "business_id" OR $key == "classification_id" OR $key == "created_at" OR $key == "updated_at") continue;
				$temp[$key] = $value;
			}
		}
		
		if( !is_null( $user ) )
		{
			foreach( $user AS $key => $value )
			{
				//dd($user);
			}
		}

		foreach($temp AS $key => $value)
		{
			$this->$key = $value;
		}
	}
}