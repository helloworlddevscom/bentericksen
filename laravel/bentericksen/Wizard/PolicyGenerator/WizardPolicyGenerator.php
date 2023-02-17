<?php

namespace Bentericksen\Wizard\PolicyGenerator;

class WizardPolicyGenerator 
{
	protected $business;
	protected $settings;
	
	public function __construct( $business, $settings )
	{
		$this->business = $business;
		$this->settings = $settings;
	}
}