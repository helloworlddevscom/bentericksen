<?php

namespace Bentericksen\Policy\PolicyArgs;

use DB;

class Sickleave
{
	private $business;
	private $settings;
	
	private $args = [];
	
	public function __construct( $business, $settings )
	{
		$this->business = $business;
		$this->settings = $settings;
		
		$this->setArgs();
		
	}
	
	public function fetch()
	{
		return $this->args;
	}
	
	private function setArgs()
	{
		$this->args['offered'] = $this->settings->sickleave->is_offered;
		
		foreach( $this->settings->classifications as $key => $classification )
		{
			$this->args['classifications'][$key]['is_enabled'] = $classification->is_enabled;
			$this->args['classifications'][$key]['is_base'] = $classification->is_base;
			$this->args['classifications'][$key]['name'] = $classification->name;
			
			foreach($classification->sickleave AS $ke => $val )
			{
				$this->args['classifications'][$key][$ke] = $val;
			}
		}
		
		foreach( $this->args['classifications'] AS $key => $classification )
		{
			$this->args['classifications'][$key] = (object)$classification;
		}
	}
}