<?php

namespace Bentericksen\Employees;

use App\User;
use Bentericksen\Employees\Employee;

class EmployeeFactory
{
	
	public static function Retrieve($userId)
	{
		$user = User::find($userId);
		
		if(! is_null( $user ) )
		{
			$temp = new Employee($user);
			$temp->parseUserData();
			return $temp;
		} else {
			throw new Exception("Invalid User/Employee");
		}		
	}
	
}