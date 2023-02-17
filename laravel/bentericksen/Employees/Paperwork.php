<?php

namespace Bentericksen\Employees;

use DB;

class Paperwork
{
	private $userId;
	
	public function __construct($userId)
	{
		$this->userId = $userId;
	}
	
	public function save($request)
	{		
		DB::table('users_paperwork')->where('user_id', $this->userId)->delete();
		foreach( $request AS $key => $value )
		{
			if( (int)$value === 1 )
			{
				$data = ['user_id' => $this->userId, 'paperwork_id' => $key];
				DB::table('users_paperwork')->insert($data);
			}
		}
		
	}
	
}