<?php

namespace Bentericksen\LicenseAgreement;

use \Illuminate\Contracts\View\View;
use \Illuminate\Users\Repository as UserRepository;
use Auth;
use App\User;
use Bentericksen\Employees\User as EmployeeUser;
use App\Business;
use DB;

class LicenseAgreement
{
	protected $user;
	public $role;
	
	public function __construct(\Bentericksen\Employees\User $user)
	{	
		$this->user = $user;
		
		$this->findRole();
		
		return $this->role;
	}
	
	private function findRole()
	{
		if($this->user->checkHasRole('admin'))
		{
			$this->role = 'admin';			
		}
		elseif($this->user->checkHasRole('consultant'))
		{
			$this->role = 'consultant';
		} 
		elseif($this->user->checkHasRole('owner'))
		{
			$this->role = 'user';
		}
		elseif($this->user->checkHasRole('manager'))
		{
			$this->role = 'user';
		}
		elseif($this->user->checkHasRole('employee'))
		{
			$this->role = 'employee';
		} else {
			throw new \Exception('User does not have a role');
		}
	}
}