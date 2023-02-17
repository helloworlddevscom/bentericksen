<?php

namespace Bentericksen\Employees;

//use App\User;
use DB;

class NonBenefit
{
	protected $user;
	protected $regularFiles = [];
	protected $confidentialFiles = [];
	
	public function __construct( $userId )
	{
		$this->user = \App\User::find( $userId );
		
		$this->regularFiles = DB::table( 'file_uploads' )
								->where( 'user_id', $this->user->id )
								->where( 'business_id', $this->user->business_id )
								->where( 'status', 'active' )
								->where( 'type', 'regular' )
								->get();
								
		$this->confidentialFiles = DB::table( 'file_uploads' )
									->where( 'user_id', $this->user->id )
									->where( 'business_id', $this->user->business_id )
									->where( 'status', 'active' )
									->where( 'type', 'confidential' )
									->get();	

	}
	
	public function regularFiles()
	{
		return $this->regularFiles;
	}

	public function confidentialFiles()
	{
		return $this->confidentialFiles;
	}
	
}