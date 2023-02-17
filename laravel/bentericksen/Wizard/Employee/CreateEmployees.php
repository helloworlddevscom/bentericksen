<?php

namespace Bentericksen\Wizard\Employee;

use DB;
use App\User;

class CreateEmployees
{
	
	private $user = [
		'first_name',
		'middle_name',
		'last_name',
		'prefix',
		'suffix',
		'email',
		'address1',
		'address2',
		'city',
		'zip',
		'state',
		'phone1',
		'phone1_type',
		'phone2',
		'phone2_type',
		'hired',
		'salary',
		'dob',
	];
	
	private $pending = [];
	
	public function __construct( $businessId, $data )
	{
		$this->businessId = $businessId;
		
		foreach( $data AS $key => $value )
		{
			$temp = [];
			foreach( $value as $ke => $val )
			{
				if( in_array( $ke, $this->user ) )
				{
					if( $ke == "zip" )
					{
						$temp['user'][ 'postal_code' ] = (string)$val;
					} elseif($ke == "dob") {
						$temp['user'][ $ke ] = $val->format('Y-m-d') . " 00:00:00";
					} else {
						$temp['user'][ $ke ] = (string)$val;
					}
				}
			}
			
			$this->pending[] = $temp;
		}
		
		$this->createUsers();
	}
	
	private function createUsers()
	{
		$now = new \Carbon\Carbon;
		
		foreach( $this->pending AS $pending )
		{
			$pending['user']['business_id'] = $this->businessId;
			$pending['user']['created_at'] = $now->format('Y-m-d h:i:s');
			$pending['user']['password'] = bcrypt(str_random(12));
			$pending['user']['can_access_system'] = 0;
			$pending['user']['employee_wizard'] = 1;
			$pending['user']['hired'] = $now->format('Y-m-d h:i:s');
			$user = User::create($pending['user']);
			$user->attachRole(5);	

			for($i = 0; $i < 3; $i++)
			{
				$emergencyContact = new \App\EmergencyContact;
				$emergencyContact->user_id = $user->id;
				$emergencyContact->name = " ";
				$emergencyContact->phone1 = " ";
				$emergencyContact->phone1_type = "cell";
				$emergencyContact->phone2 = " ";
				$emergencyContact->phone2_type = "cell";
				$emergencyContact->phone3 = " ";
				$emergencyContact->phone3_type = "cell";
				$emergencyContact->relationship = "cell";
				$emergencyContact->is_primary = ($i == 0 ? 1 : 0);
				$emergencyContact->save();
			}

			DB::table('driver_licenses')->insert([ 'user_id' => $user->id ]);
		}
	}
}