<?php

namespace Bentericksen\Permissions;

use Bentericksen\Employees\User;
use Carbon\Carbon;
use DB;

use App\BusinessPermission;
use Bentericksen\Employees\Permission;

class Permissions
{
	protected $result;
	
	private $lookup = [ 
		'emergency'		=> 'emergencyContacts',
		'salary'		=> 'salary',
		'user'			=> 'user',
		'licensure' 	=> 'licensure',
		'license'		=> 'license',
		'attendance'	=> 'attendance',
		//'history'	=> 'history'
		'paperwork'		=> 'paperwork',
		'permission'	=> 'permission',
		'jobs'			=> 'jobDescription',
	];
	
	public function __construct($businessId, $permission)
	{
		$this->result = BusinessPermission::where('business_id', $businessId)->first()->$permission;
	}
	
	public function getPermissions()
	{
		return $this->result;
	}
}