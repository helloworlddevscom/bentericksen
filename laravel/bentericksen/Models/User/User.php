<?php

namespace Bentericksen\Models\User;

class User
{
	protected $userId;
	
	private $user;
	
	protected $business;
	
	protected $status;
	
	protected $benefits;
	
	protected $nonBenefits;
	
	public $personal;
	
	public $contact;
	
	protected $history;
	
	protected $authorization;
	
	//protected $
	
	public function __construct($userId)
	{
		$this->userId = $userId;
		
		$this->user = \App\User::findOrFail($userId);
		$this->business = \App\Business::findOrFail($this->user->business_id);
		$this->contact = $this->getContact();
		$this->personal = $this->getPersonal();
	}
	
	private function getPersonal()
	{
		$personal = new \stdClass;
		$personal->dob = ( is_null( $this->user->dob ) ? "" : $this->user->dob);
		$personal->age = ( is_null( $this->user->age ) ? "" : $this->user->age);
		$personal->emergencyContact = $this->getEmergencyContact();
		
		return $personal;
	}
	
	private function getBenefits()
	{
		
		
	}
	
	private function getContact()
	{
		$contact = new \stdClass;
		$contact->address1 		= ( is_null( $this->user->address1 ) ? "" : $this->user->address1);
		$contact->address2 		= ( is_null( $this->user->address2 ) ? "" : $this->user->address2);
		$contact->city 			= ( is_null( $this->user->city ) ? "" : $this->user->city);
		$contact->state 		= ( is_null( $this->user->state ) ? "" : $this->user->state);
		$contact->postal_code 	= ( is_null( $this->user->postal_code ) ? "" : $this->user->postal_code);
		$contact->phone1 		= ( is_null( $this->user->phone1 ) ? "" : $this->user->phone1);
		$contact->phone2 		= ( is_null( $this->user->phone2 ) ? "" : $this->user->phone2);
		$contact->phone3 		= ( is_null( $this->user->phone3 ) ? "" : $this->user->phone3);
		$contact->phone1_type	= ( is_null( $this->user->phone1_type ) ? "" : $this->user->phone1_type);
		$contact->phone2_type	= ( is_null( $this->user->phone2_type ) ? "" : $this->user->phone2_type);
		$contact->phone3_type	= ( is_null( $this->user->phone3_type ) ? "" : $this->user->phone3_type);
		$contact->email 		= $this->user->email;
		
		return $contact;
	}
	
	
	private function getEmergencyContact()
	{
		return [];
	}
	
}