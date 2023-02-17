<?php

namespace Bentericksen\BonusPro\Reports;

use App\BonusPro\Plan;

abstract class ReportsAbstract
{
    /**
     * @var array
     */
    public $reportData;

    /**
     * @var string
     */
    public $endDate;

    /**
     * @var string
     */
    public $startDate;

    /**
     * @var Plan
     */
    public $plan;

    /**
     * @var array
     */
    public $settings;

    /**
     * Builds an array with the data for the report
     *
     * @return ReportsAbstract
     */
    abstract protected function _initializeReportData() : self;

    /**
     * Renders Report
     * @return mixed
     * @throws \Throwable
     */
    public function render()
    {
        $type = !empty($this->settings['reportType']) ? $this->settings['reportType'] : 'detailed';

        return view($this->view . '.' . $type)
            ->with('settings', $this->settings)
            ->with('business', $this->plan->business->name)
            ->with('plan', $this->plan)
            ->with('startDate', $this->startDate)
            ->with('endDate', $this->endDate)
            ->with('reportData', $this->reportData)
            ->render();
    }

    /**
     * Checks if month is supposed to be included in report.
     *
     * @param $month
     * @return bool
     */
    public function inReport($month)
    {
        $decision = true;

        $monthTimestamp = strtotime($month->month . '/01/' . $month->year);

        if ($monthTimestamp < strtotime($this->startDate) || $monthTimestamp > strtotime($this->endDate) || !$month->finalized) {
            $decision = false;
        }

        return $decision;
    }

    /**
     * Determines if a user is the desired type for this report (e.g., staff, hygiene, all)
     * @param App\User $user
     * @return boolean
     */
    public function userIsDesiredType($user) {
      if ($this->settings['employeesFunds'] === 'staff_employees' && $user->bp_employee_type === 'hygienist') {
        return false;
      }
      if ($this->settings['employeesFunds'] === 'hygiene_employees' && $user->bp_employee_type !== 'hygienist') {
        return false;
      }
      return true;
    }
}
