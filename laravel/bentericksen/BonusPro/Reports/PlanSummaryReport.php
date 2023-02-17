<?php

namespace Bentericksen\BonusPro\Reports;

use App\BonusPro\Month;
use App\BonusPro\Plan;
use Carbon\Carbon;

class PlanSummaryReport extends ReportsAbstract
{
    /**
     * @var string
     */
    public $view = 'bonuspro.reports.plan_summary';

    /**
     * @var array
     */
    protected $monthsInReport;

    /**
     * @var array
     */
    protected $staffIds = [];

    /**
     * @var array
     */
    protected $hygieneIds = [];

    public function __construct(Plan $plan, array $settings)
    {
        $this->plan = $plan;
        $this->settings = $settings;
        $days = Carbon::createFromDate($this->settings['endDateYear'], $this->settings['endDateMonth'])->daysInMonth;
        $this->startDate = $this->settings['startDateMonth'] . '/01/' . $this->settings['startDateYear'];
        $this->endDate = $this->settings['endDateMonth'] . '/' . $days . '/' . $this->settings['endDateYear'];
        $this->reportData = [];

        $this->setMonthsInReport()->setEmployees()->_initializeReportData();
    }

    /**
     * Initialize Months.   Do not include initialization months
     *
     * @return PlanSummaryReport
     */
    protected function setMonthsInReport(): self
    {
        $startDate = Carbon::createFromTimestamp(strtotime($this->startDate));
        $endDate = Carbon::createFromTimestamp(strtotime($this->endDate));
        $arr = [];

        foreach ($this->plan->getRunningMonths() as $month) {
            $monthDate = Carbon::createFromDate($month->year, $month->month, 1);

            if (!$monthDate->between($startDate, $endDate) || !$month->finalized) {
                continue;
            }

            array_push($arr, $month);
        }

        $this->monthsInReport = $arr;

        return $this;
    }

    /**
     * Initialize Employee IDs per employee Type.
     *
     * @return PlanSummaryReport
     */
    protected function setEmployees(): self
    {
        foreach ($this->plan->users as $user) {
            if ($user->bp_employee_type === 'hygienist') {
                array_push($this->hygieneIds, $user->id);
            } else {
                array_push($this->staffIds, $user->id);
            }
        }

        return $this;
    }

    /**
     * Builds an array with the data for the report
     *
     * @return ReportsAbstract
     */
    protected function _initializeReportData(): ReportsAbstract
    {
        foreach ($this->monthsInReport as $month) {
            $arr = [];
            $arr['date'] = $month->month . '/1/' . $month->year;
            $arr['production_amount'] = $month->production_amount;
            $arr['collection_amount'] = $month->collection_amount;
            $arr['production_collection_average'] = $month->production_collection_average;
            $arr['staff_percentage'] = $month->staff_percentage;
            $arr['staff_salaries'] = $this->_getUsersSalaries('staff', $month);
            $arr['staff_hours'] = $this->_getUsersHours('staff', $month);
            $arr['hygienists_salaries'] = $this->_getUsersSalaries('hygiene', $month);
            $arr['hygienists_hours'] = $this->_getUsersHours('hygiene', $month);

            array_push($this->reportData, $arr);
        }

        return $this;
    }

    /**
     * Returns the Salary total for given month and employee Type
     * @param $type string
     * @param Month $month
     * @return float
     */
    protected function _getUsersSalaries($type, Month $month): float
    {
        $salaries = 0;

        if (!empty($this->staffIds)) {
            foreach($month->employeeData as $userData) {
                // only considering the users in the { $type . 'Ids' } array.
                if (!in_array($userData->user_id, $this->{ $type . 'Ids'})) {
                    continue;
                }
                $salaries += $userData->gross_pay;
            }
        }

        return $salaries;
    }


    /**
     * Returns the Hours Worked total for given month and employee Type
     * @param $type string
     * @param Month $month
     * @return float
     */
    protected function _getUsersHours($type, Month $month): float
    {
        $hours = 0;

        if (!empty($this->staffIds)) {
            foreach($month->employeeData as $userData) {
                // only considering the users in the { $type . 'Ids' } array.
                if (!in_array($userData->user_id, $this->{ $type . 'Ids'})) {
                    continue;
                }
                $hours += $userData->hours_worked;
            }
        }

        return $hours;
    }
}
