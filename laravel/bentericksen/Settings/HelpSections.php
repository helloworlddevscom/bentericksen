<?php

namespace Bentericksen\Settings;

class HelpSections
{
	private $sections = [
			'a' 		=> 'Administration',
			'o'			=> 'Owner/Manager',
			'c'			=> 'Consultant',
			'e'			=> 'Employee',
	];
	
	private $subSections = [
		'a'		=> [
			'1301'		=>	'Sort',
		],
		'o'		=> [
			'2701'		=> 'Optional/Required',
			'2901'		=> 'Calculate Number Of Employees',
			'2902'		=> 'Why Number Of Employees Important',
			'2903'		=> 'Medical - Cap Amount',
			'2904'		=> 'Medical - Consideration Pay',
			'2905'		=> 'Vision - Cap Amount',
			'2906'		=> 'Dental:PDBA - Cap Amount',
			'2907'		=> 'Dental - Cap Amount',
			'2908'		=> 'PTO/Vac - Benefits Are Earned On',
			'2909'		=> 'PTO/Vac - How Are Benefits Provided',
			'2910'		=> 'PTO/Vac - Waiting Period To Start Using',
			'2911'		=> 'PTO/Vac - Do You Allow Carry-Over',
			'2912'		=> 'Holiday',
			
			'3001'		=> 'Status',
			'3002'		=> 'Additional Employees',
			'3003'		=> 'Total Employees',
			'3004'		=> 'Non-Employee List',
			'3005'		=> 'Why number of employees important',
			'3006'		=> 'Count number of employees',
			
			'3051'		=> 'Terminate',
			'3052'		=> 'Benefit Date',
			'3053'		=> 'Benefit Years of Service',
			'3054'		=> 'Driver\'s License',
			'3055'		=> 'PTO/Vac - Available Balance',
			'3056'		=> 'PTO/Vac - Amount Earned',
			'3057'		=> 'Leave of Absence - Add New Leave',
			'3059'		=> 'Regular File - Print All',
			
			'3060'		=> 'System Access - Can Access The System',
			'3061'		=> 'System Access - Included In Employee Count',
			'3062'		=> 'System Access - Receives Benefits',
			'3063'		=> 'System Access - Permissions Level',
			'3064'		=> 'System Access - Resend Activation Email',
			'3065'		=> 'Sick Leave - Available Balance',
			'3066'		=> 'Sick Leave - Amount Earned',
			
			'3201'		=> 'Enter Employee Hours For Calculating Benefits',
			
			'3301'		=> 'Purge Date',
			
			'3501'		=> 'Download Excel Template',
			'3502'		=> 'PTO/Vac - Earned',
			'3503'		=> 'PTO/Vac - Available',
			'3504'		=> 'Employee access to information',
			
			'3601'		=> 'Assignment Level',
			
			'3801'		=> 'Outline - Employee Status',
			'3802'		=> 'Outline - Job Summary',
			'3803'		=> 'Outline - Qualifications',
			'3804'		=> 'Outline - Essential Duties',
			'3805'		=> 'Outline - Knowledge / Skills / Abilities',
			'3806'		=> 'Outline - Education / Experience',
			'3807'		=> 'Outline - Special Requirements / Certifications / Licenses',
			'3808'		=> 'Outline - Physical / Environmental Requirements',
			
			'4601'		=> 'Permissions',
			
			'4701'		=> 'Users Who Receive Business Emails',
			//'3056'		=> '',
		]
	];
	
	public function section($key = null)
	{
		if( is_null( $key ) )
		{
			return $this->sections;
		} else {
			if( isset( $this->sections[ $key ] ) )
			{
				return $this->sections[ $key ];
			}
		}
	}

	public function subSections( $key = null )
	{
		if( is_null( $key ) )
		{
			return $this->subSections;
		} else {
			if( isset( $this->subSections[ $key ] ) )
			{
				return $this->subSections[ $key ];
			}
		}
	}
	
}