<?php

namespace Bentericksen\Employees;

use DB;

class Permission
{
	
	private $salary;
	private $userId;
	private $rate;
	private $user;
	
	public function __construct($userId)
	{
		$this->userId = $userId;
		$this->user = \App\User::find( $userId );
	}
	
	public function save($request)
	{
		$this->user->detachRoles([2,3,5]);
		
		$role = $request['user_role'];
		if( $role == 1 OR $role == 4 )
		{
			$role = 5;
		}
		
		$this->user->attachRole( $role );
		
	}
	
	public function getSalary()
	{
		return $this->salary;
	}
	
	public function getRate()
	{
		return $this->rate;
	}
	
}