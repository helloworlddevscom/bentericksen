<?php

namespace Bentericksen\Policy\SpecialRules;

class MarylandFlexibleLeave
{
	protected $state = "MD";
	
	protected $rules = [
		"123"	=> [
			'status' => 'active',
		]
	];
	
	
	
	private function passes()
	{
		//insert or activate Flex Policy
	}
	
	private function fails()
	{
		//do fail action
		//Disable Flex Policy
	}
}