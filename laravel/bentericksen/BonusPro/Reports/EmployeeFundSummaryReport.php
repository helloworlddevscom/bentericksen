<?php

namespace Bentericksen\BonusPro\Reports;

use App\BonusPro\Fund;
use App\BonusPro\Plan;
use App\User;
use Carbon\Carbon;

class EmployeeFundSummaryReport extends ReportsAbstract
{
    /**
     * @var string
     */
    public $view = 'bonuspro.reports.employee_fund_summary';

    public function __construct(Plan $plan, array $settings)
    {
        $this->plan = $plan;
        $this->settings = $settings;
        $days = Carbon::createFromDate($this->settings['endDateYear'], $this->settings['endDateMonth'])->daysInMonth;
        $this->startDate = $this->settings['startDateMonth'] . '/01/' . $this->settings['startDateYear'];
        $this->endDate = $this->settings['endDateMonth'] . '/' . $days . '/' . $this->settings['endDateYear'];
        $this->reportData = [
            'all' => [],
            'admin/assistant' => [],
            'hygienist' => [],
            'funds' => []
        ];
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
            // Check if the Funds are being requested by the report. If not, skip funds.
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
     * @param User $user
     * @return array
     */
    protected function _getUserMonthData(User $user): array
    {
        $grossPayTotal = 0;
        $hoursWorkedTotal = 0;
        $amountReceivedTotal = 0;
        $grandTotal = 0;
        foreach ($this->plan->getRunningMonths() as $month) {
            $monthTimestamp = strtotime($month->month . '/01/' . $month->year);

            if ($monthTimestamp < strtotime($this->startDate) || $monthTimestamp > strtotime($this->endDate) || !$month->finalized) {
                continue;
            }
            $userMonthData = $month->employeeData()->where('user_id', $user->id)->first();
            $grossPayTotal += $userMonthData ? $userMonthData->gross_pay : null;
            $hoursWorkedTotal += $userMonthData ? $userMonthData->hours_worked : null;
            $amountReceivedTotal += $userMonthData ? $userMonthData->amount_received : null;

            $grandTotal += $userMonthData ? $userMonthData->gross_pay : null;
            $grandTotal += $userMonthData ? $userMonthData->amount_received : null;
        }
        return [
            'name'            => $user->full_name,
            'gross_pay'       => $grossPayTotal,
            'hours_worked'    => $hoursWorkedTotal,
            'amount_received' => $amountReceivedTotal,
            'total_received'  => $grandTotal,
        ];
    }

    /**
     * Builds the data array for the report.
     * @param Fund $fund
     * @return array
     */
    protected function _getFundMonthData(Fund $fund)
    {
        $amountReceivedTotal = 0;
        foreach ($this->plan->getRunningMonths() as $month) {
            // if Month is not supposed to be included in the report, or if there is no fund data available, skip month.
            if (!$this->inReport($month) || !$month->funds->count()) {
                continue;
            }

            $fundMonthData = $month->funds->where('fund_id', $fund->id)->first();

            if (!$fundMonthData) {
                continue;
            }

            $amountReceivedTotal += $fundMonthData->amount_received;
        }

        return [
            'name'            => $fund->fund_name,
            'gross_pay'       => null,
            'hours_worked'    => null,
            'amount_received' => $amountReceivedTotal,
            'total_received'  => $amountReceivedTotal,
        ];
    }

    /**
     * Prepares Employees data for report.
     * @return EmployeeFundSummaryReport
     */
    protected function prepareEmployeesData(): self
    {
        // generating the report for all users/funds.
        foreach ($this->plan->users as $user) {
            if ($this->userIsDesiredType($user)) {
                // Grouping for the report. Summary reports do not group by type. The grouping for detailed reports will be
                // the User's BP Employee Type.
                $grouping = $this->settings['reportType'] == 'summary' ? 'all' : $grouping = $user->bp_employee_type;

                array_push($this->reportData[$grouping], $this->_getUserMonthData($user));
            }
        }
        return $this;
    }

    /**
     * Prepares funds data for report.
     * @return EmployeeFundSummaryReport
     */
    protected function prepareFundsData(): self
    {
        foreach ($this->plan->funds as $fund) {
            $data = $this->_getFundMonthData($fund);
            if ($data) {
                // Grouping for the report. Summary reports do not group by type. The grouping for detailed
                // reports will be "Funds".
                $grouping = $this->settings['reportType'] == 'summary' ? 'all' : 'funds';
                array_push($this->reportData[$grouping], $data);
            }
        }
        return $this;
    }

    /**
     * Prepares single record data for report.
     * @return EmployeeFundSummaryReport
     */
    protected function prepareSingleRecord(): self
    {
        // the employee/fund ID is passed in using the format 'e-11' or 'f-12'
        // where the 'e' or 'f' indicates the record type and the number after
        // the hyphen is the row ID.
        $type = substr($this->settings['singleEmployeeFundId'], 0, 1);
        $id = substr($this->settings['singleEmployeeFundId'], 2);
        if ($type === 'e') {
            $user = $this->plan->users->where('id', $id)->first();
            $this->reportData['Single Emp/Fund'][] = $this->_getUserMonthData($user);
        } else {
            $fund = $this->plan->funds->where('id', $id)->first();
            $this->reportData['Single Emp/Fund'][] = $this->_getFundMonthData($fund);
        }
        return $this;
    }
}
