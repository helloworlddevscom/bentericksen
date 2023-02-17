<?php namespace Bentericksen\Models\User;

class UserUpdate {
	
	protected $userId;
	
	public function __construct($userId)
	{
		$this->userId = $userId;
		
	}

	public function user(\App\User $model, $array)
	{
		foreach($array AS $key => $row)
		{
			$model->find($key)->update($row);
		}
	}
	
	public function emergencyContacts(\App\EmergencyContact $model, $array)
	{
		foreach($array AS $key => $row)
		{
			$model->find($key)->update($row);
		}
	}

}