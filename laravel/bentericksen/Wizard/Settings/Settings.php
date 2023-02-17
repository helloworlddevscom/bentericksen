<?php

namespace Bentericksen\Wizard\Settings;

use Bentericksen\Wizard\Settings\SettingsLoad;
use Bentericksen\Wizard\Settings\SettingsSave;
use Bentericksen\Wizard\Classifications\Classifications;

use DB;

class Settings
{
	private $business;
	
	private $settings;
	
	private $settingsArray;
	
	private $holidays = [];
	
	//Business Object
	public function __construct( $business )
	{
		$this->business = $business;
		$this->load();
	}
	
	public function load()
	{
		$settings = new SettingsLoad( $this->business->id );	
		$this->settings = $settings->load();
		
		//this should be its own class
		$this->loadHolidays();
		
		return $this;
	}
	
	public function getSettings()
	{
		return $this->settings;
	}
	
	
	//Loops through data and assigned is to proper variables for storage into DB. Currently this handles everything and passes classifications and holidays off. 
	//Will need to rework this. It is way to tightly coupled and difficult to read.
	public function set( $data )
	{
		foreach($data AS $key => $value)
		{
			if($key == "holiday" && isset($value['new']))
			{
				$new = $this->createNewHoliday( $value['new'] );
				foreach( $new as $key => $value )
				{
					$data['holiday'][$key] = $value;
				}
				unset($data['holiday']['new']);
			}
			
			if( $key == "classification" )
			{
				$this->setClassification( $value );
			} else {
				$this->settings->{$key} = $value;
			}
		}
	}
	
	public function save()
	{
		$settings = new SettingsSave( $this->business->id );
		$settings->set( $this->settings );
		$settings->save();
	}
	
	public function get( $key )
	{
		if( $key == "state" )
		{
			return $this->business->state;
		}
		
		if( isset($this->settings->{$key} ) )
		{
			return $this->settings->$key;
		}
		
		return null;
	}
	
	public function getHolidays()
	{
		return $this->holidays;
	}
	
	private function loadHolidays()
	{
		$holidays = DB::table('holidays')
						->whereIn( 'business_id', [ 0, $this->business->id] )
						->get();
						
		$this->holidays = $holidays;
	}
	
	private function createNewHoliday( $data )
	{
		$return = [];
		foreach( $data AS $key => $value )
		{
			if($value['name'] == "") continue;
			
			$fields = [
				'business_id'	=> $this->business->id,
				'name'			=> $value['name'],
				'info'			=> $value['info'],
			];
			
			
			$insertId = DB::table('holidays')->insertGetId($fields);
			
			$return[ $insertId ] = [
				'is_enabled'	=> $value['is_enabled']
			];
		}
		
		return $return;
	}
	
	//Need to move this out into a data object
	private function setClassification( $data )
	{
		$classification = new Classifications( $this->business->id );
		
		//create new classification data
		if( isset( $data[ 'new' ] ) )
		{
			$returns = $classification->createNew( $data['new'] );
			
			foreach( $returns AS $return )
			{
				$this->settings->classifications->{$return['id']} = $return['data'];
			}
			
			//remove created classifications
			unset( $data[ 'new' ] );
		}
		
		foreach( $data AS $key => $value )
		{
			if(key($value) === "vacation_pto")
			{
				if(isset( $value['vacation_pto']['new'] ))
				{
					foreach($value['vacation_pto']['new'] AS $new)
					{
						$value['vacation_pto']['row'][] = $new;
					}
					
					unset( $value['vacation_pto']['new'] );
				}
			}
			
			foreach( $value AS $ke => $val )
			{
				if( is_array( $val ) )
				{
					$val = json_decode( json_encode( $val ) );
				}
				
				$this->settings->classifications->{$key}->{$ke} = $val;	
			}
		}
	}	
}