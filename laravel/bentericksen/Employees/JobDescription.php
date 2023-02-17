<?php

namespace Bentericksen\Employees;

use DB;

class JobDescription
{
	private $userId;
	
	public function __construct($userId)
	{
		$this->userId = $userId;
	}
	
	public function save($request)
	{		
		dd( $request );
		
	}
}