<?php

namespace Bentericksen\Wizard;

use App\Business;
use Illuminate\Filesystem\Filesystem;

/**
 * Class WizardGenerate
 * @package Bentericksen\Wizard
 */
class WizardGenerate
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var Business
     */
    protected $business;

    /**
     * @var mixed
     */
    private $stepOrder;
    /**
     * @var
     */
    protected $steps;

    /**
     * @param Business $business
     * @param Filesystem $files
     */
    public function __construct(Business $business, Filesystem $files)
	{
		$this->business = $business;
		$this->files = $files;

		$this->stepOrder = include base_path() . "/bentericksen/Wizard/WizardOrder.php";
		
		$this->path = base_path() . "/bentericksen/Wizard/Steps";
		
		$this->loadStepFiles($this->files->files($this->path));
		$this->orderSteps();
	}

    /**
     * @return mixed
     */
    public function getSteps()
	{
		return $this->steps;
	}

    /**
     * @return string
     */
    public function getJsonSteps()
	{
		return json_encode($this->steps);
	}

    /**
     * @param $stepFiles
     */
    private function loadStepFiles($stepFiles)
	{
		foreach($stepFiles AS $stepFile)
		{
			$file = include $stepFile;
			
			if($this->filterStep($file['rules']))
			{			

				$this->steps[$file['benefitType']][]	= [
					'name'	=> $file['name'],
					'view'	=> $file['view'],
					'rules' => $file['rules'],
					'validations' => $file['validations'],
					'priority' => $file['priority'],
					'standalone' => (isset($file['standalone']) ? $file['standalone'] : false),
				];
			}
		}		
	}

    /**
     * @param $rules
     * @return bool
     */
    private function filterStep($rules)
	{
		$ruleKeys = array_keys($rules);
		
		foreach($ruleKeys AS $key)
		{
			$method = "check" . ucfirst($key);
			if(method_exists($this, $method))
			{
				if( ! $this->$method($rules[$key]) )
				{
					return false;
				}
			}
		}
		
		return true;
	}


    /**
     *
     */
    private function orderSteps()
	{
		$temp = [];
		
		foreach($this->stepOrder AS $order)
		{
			if(isset($this->steps[$order]))
			{
				$temp[$order] = $this->orderStep($this->steps[$order]);
			}
		}
		
		$this->steps = $temp;
		
	}


    /**
     * @param $steps
     * @return array
     */
    private function orderStep($steps)
	{
		$tempArray = [];
		$returnArray = [];
		
		foreach($steps AS $step)
		{
			$tempArray[$step['priority']][] = $step;
		}
		
		ksort($tempArray);

		foreach($tempArray AS $temp)
		{
			foreach($temp As $tmp)
			{
				$returnArray[] = $tmp;
			}
		}
		
		return $returnArray;
	}
	
	
	/*checks*/
    /**
     * @param $businessId
     * @return bool
     */
    private function checkBusinessId($businessId)
	{
		if(is_array($businessId))
		{	
			return in_array($this->business->id, $businessId);
		}
		
		if($this->business->id === $businessId)
		{
			return true;
		}
		
		return false;		
	}

    /**
     * @param $businessType
     * @return bool
     */
    private function checkBusinessType($businessType)
	{
		if(is_array($businessType))
		{	
			return in_array($this->business->type, $businessType);
		}
		
		if($this->business->type === $businessType)
		{
			return true;
		}
		
		return false;		
	}

    /**
     * @param $employeeMin
     * @return bool
     */
    private function checkEmployeeMin($employeeMin)
	{
		if($this->business->employees < $employeeMin)
		{
			return false;
		}
		
		return true;
	}

    /**
     * @param $employeeMax
     * @return bool
     */
    private function checkEmployeeMax($employeeMax)
	{
		if($this->business->employees > $employeeMax)
		{
			return false;
		}
		
		return true;
	}

    /**
     * @param $state
     * @return bool
     */
    private function checkState($state)
	{
		if(is_array($state))
		{	
			return in_array($this->business->state, $state);
		}
		
		if($this->business->state === $state)
		{
			return true;
		}
		
		return false;
	}	
	
}