<?php

namespace Bentericksen\Wizard;

/**
 * Class Policy
 * @package Bentericksen\Wizard
 */
class Policy
{
	private $businessId;
	private $defaultBusinessSettings;
	private $defaultClassificationSettings;
	private $defaultUserSettings;
	
	public function __construct($businessId, $defaultBusinessSettings, $defaultClassificationSettings, $defaultUserSettings)
	{
		$this->businessId = $businessId;
		$this->defaultBusinessSettings = $defaultBusinessSettings;
		$this->defaultClassificationSettings = $defaultClassificationSettings;
		$this->defaultUserSettings = $defaultUserSettings;
	}
}