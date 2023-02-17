<?php

namespace Bentericksen\History;

use DB;

class History
{
	private $pending = [];
	
	public function add($args)
	{
		$this->pending[] = $args;
	}
	
	public function save()
	{
		foreach($this->pending as $pending)
		{
			DB::table('history')
				->insert($pending);		
		}
	}
}