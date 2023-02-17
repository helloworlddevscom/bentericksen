<?php

namespace Bentericksen\BonusPro\Reports;

use App\BonusPro\Fund;
use App\BonusPro\Plan;
use App\User;
use Carbon\Carbon;

class IndividualEmployeeFundSummaryReport extends ReportsAbstract
{
    /**
     * @var string
     */
    public $view = 'bonuspro.reports.individual_emp_fund';

    public function __construct(Plan $plan, array $settings)
    {
        $this->plan = $plan;
        $this->settings = $settings;
        $days = Carbon::createFromDate($this->settings['endDateYear'], $this->settings['endDateMonth'])->daysInMonth;
        $this->startDate = $this->settings['startDateMonth'] . '/01/' . $this->settings['startDateYear'];
        $this->endDate = $this->settings['endDateMonth'] . '/' . $days . '/' . $this->settings['endDateYear'];
        $this->reportData = [];
        $this->_initializeReportData();
    }

    /**
     * Builds an array with the data for the report "Employee/Fund Summary"
     *
     * @return ReportsAbstract
     */
    protected function _initializeReportData(): ReportsAbstract
    {
        // check if the requested report is for a single entity (employee or fund) or all of them
        $single = $this->settings['singleEmployeeFund'];
        if (!$single) {
            // Check if Employees (Users) are being requested by the report. If not, skip users.
            if ($this->settings['employeesFunds'] == 'staff_employees' || $this->settings['employeesFunds'] == 'hygiene_employees' || $this->settings['employeesFunds'] == 'all') {
                $this->prepareEmployeesData();
            }

            // Check if Funds are being requested by the report. If not, skip funds.
            if ($this->settings['employeesFunds'] == 'funds' || $this->settings['employeesFunds'] == 'all') {
                $this->prepareFundsData();
            }
        } else {
            $this->prepareSingleRecord();
        }

        return $this;
    }

    /**
     * Builds the data array for the report.
     *
     * @param $fund Fund
     * @return array|null
     */
    protected function _getFundMonthData(Fund $fund): array
    {
        $months = [];
        $amountReceivedTotal = 0;

        foreach ($this->plan->getRunningMonths() as $month) {
            // if Month is not supposed to be included in the report, or if there is no fund data available, skip month.
            if (!$this->inReport($month) || !$month->funds->count()) {
                continue;
            }

            $fundMonthData = $month->funds()->where('fund_id', $fund->id)->first();

            if (!$fundMonthData) {
                continue;
            }

            $arr = [
                'month'           => $month->month,
                'year'            => $month->year,
                'gross_pay'       => 0,
                'hours_worked'    => 0,
                'amount_received' => $fundMonthData->amount_received,
            ];

            array_push($months, $arr);

            $amountReceivedTotal += $fundMonthData->amount_received;
        }

        return !empty($months) ? [
            'name'                => ucfirst($fund->fund_name),
            'months'              => $months,
            'hired'               => null,
            'bp_eligibility_date' => $fund->fund_start_month . '/01/' . $fund->fund_start_year,
            'terminated'          => null,
            'bp_employee_type'    => 'Fund',
            'totalGrossPay'       => null,
            'totalHoursWorked'    => null,
            'totalAmountReceived' => $amountReceivedTotal,
            'grandTotalReceived'  => $amountReceivedTotal,
        ] : [];
    }

    /**
     * Builds the data array for the report.
     *
     * @param $user
     * @return array
     */
    protected function _getUserMonthData(User $user): array
    {
        $grossPayTotal = 0;
        $hoursWorkedTotal = 0;
        $amountReceivedTotal = 0;
        $grandTotal = 0;
        $months = [];

        foreach ($this->plan->getRunningMonths() as $month) {
            // if Month is not supposed to be included in the report, skip month.
            if (!$this->inReport($month)) {
                continue;
            }

            $userMonthData = $month->employeeData()->where('user_id', $user->id)->first();

            $arr = [
                'month'           => $month->month,
                'year'            => $month->year,
                'gross_pay'       => null,
                'hours_worked'    => null,
                'amount_received' => null,
            ];

            if ($userMonthData) {
                $arr['gross_pay'] = $userMonthData->gross_pay;
                $arr['hours_worked'] = $userMonthData->hours_worked;
                $arr['amount_received'] = $userMonthData->amount_received;

                $grossPayTotal += $userMonthData->gross_pay;
                $hoursWorkedTotal += $userMonthData->hours_worked;
                $amountReceivedTotal += $userMonthData->amount_received;

                $grandTotal += $userMonthData->gross_pay;
                $grandTotal += $userMonthData->amount_received;
            }

            array_push($months, $arr);
        }

        return [
            'name'                => $user->full_name,
            'months'              => $months,
            'hired'               => $user->hired,
            'bp_eligibility_date' => $user->bp_eligibility_date,
            'terminated'          => $user->terminated,
            'bp_employee_type'    => $user->bp_employee_type,
            'totalGrossPay'       => $grossPayTotal,
            'totalHoursWorked'    => $hoursWorkedTotal,
            'totalAmountReceived' => $amountReceivedTotal,
            'grandTotalReceived'  => $grandTotal,
        ];
    }

    /**
     * Prepares employee data for report.
     * @return IndividualEmployeeFundSummaryReport
     */
    protected function prepareEmployeesData(): self
    {
        foreach ($this->plan->users as $user) {
            if ($this->userIsDesiredType($user)) {
                array_push($this->reportData, $this->_getUserMonthData($user));
            }
        }
        return $this;
    }

    /**
     * Prepares funds data for report.
     * @return IndividualEmployeeFundSummaryReport
     */
    protected function prepareFundsData(): self
    {
        foreach ($this->plan->funds as $fund) {
            $data = $this->_getFundMonthData($fund);
            if ($data) {
                array_push($this->reportData, $data);
            }
        }
        return $this;
    }

    /**
     * Prepares single record for report.
     * @return IndividualEmployeeFundSummaryReport
     */
    protected function prepareSingleRecord(): self
    {
        $type = substr($this->settings['singleEmployeeFundId'], 0, 1);
        $id = substr($this->settings['singleEmployeeFundId'], 2);

        // checking the type of entity being requested: e - Employee / f - Fund
        if ($type === 'e') {
            $data = $this->_getUserMonthData($this->plan->users->find($id));
        } else {
            $data = $this->_getFundMonthData($this->plan->funds->find($id));
        }

        // if monthly is data is available, include in the report. (because of funds)
        if ($data) {
            array_push($this->reportData, $data);
        }
        return $this;
    }
}
