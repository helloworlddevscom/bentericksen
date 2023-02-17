<?php

namespace Bentericksen\BonusPro\Reports;

use App\BonusPro\Plan;
use Carbon\Carbon;

class PlanRecapReport extends ReportsAbstract
{
    /**
     * @var string
     */
    public $view = 'bonuspro.reports.plan_recap';

    /**
     * @var array
     */
    protected $hygieneIds = [];

    /**
     * @var array
     */
    private $staffIds = [];

    public function __construct(Plan $plan, array $settings)
    {
        $this->plan = $plan;
        $this->settings = $settings;
        if (!empty($this->settings['endDateYear']) && !empty($this->settings['endDateMonth'])) {
            $days = Carbon::createFromDate($this->settings['endDateYear'], $this->settings['endDateMonth'])->daysInMonth;
            $this->startDate = $this->settings['startDateMonth'] . '/01/' . $this->settings['startDateYear'];
            $this->endDate = $this->settings['endDateMonth'] . '/' . $days . '/' . $this->settings['endDateYear'];
        }
        $this->setEmployees()->_initializeReportData();
    }

    /**
     * Initialize Employee IDs per employee Type.
     *
     * @return PlanRecapReport
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
     * @return PlanRecapReport
     */
    protected function _initializeReportData(): ReportsAbstract
    {
        $this->reportData = [];

        $this->reportData['plan_setup_date'] = $this->plan->start_month . '/' . $this->plan->start_year;
        $this->reportData['number_of_months'] = $this->plan->getRunningMonths()->count();
        $this->reportData['total_paid_to_funds'] = $this->getTotalPaidToFunds();
        $this->reportData['total_paid'] = $this->getTotalPaid();
        // staff recap
        $this->reportData['staff']['number_of_months_bonus_paid'] = $this->getNumberOfMonthsBonusPaid('staff');
        $this->reportData['staff']['percentage_of_months_bonus_paid'] = $this->getPercentageOfMonthsBonusPaid('staff');
        $this->reportData['staff']['original_bonus_percentage'] = $this->plan->getRunningMonths()->first()->staff_percentage;
        $this->reportData['staff']['current_bonus_percentage'] = $this->plan->getRunningMonths()->last()->staff_percentage;
        $this->reportData['staff']['number_of_percentage_changes'] = $this->getNumberOfPercentageChanges('staff');
        $this->reportData['staff']['current_streak'] = $this->getCurrentStreak('staff');
        $this->reportData['staff']['longest_streak'] = $this->getLongestStreak('staff');
        $this->reportData['staff']['total_hours_worked'] = $this->getTotalHoursWorked('staff');
        $this->reportData['staff']['total_bonus_amount'] = $this->getTotalBonusAmount('staff');
        $this->reportData['staff']['bonus_per_hour'] = $this->reportData['staff']['total_bonus_amount'] / $this->reportData['staff']['total_hours_worked'];
        // hygiene recap
        if ($this->plan->hygiene_plan) {
            $this->reportData['hygiene']['number_of_months_bonus_paid'] = $this->getNumberOfMonthsBonusPaid('hygiene');
            $this->reportData['hygiene']['percentage_of_months_bonus_paid'] = $this->getPercentageOfMonthsBonusPaid('hygiene');
            $this->reportData['hygiene']['original_bonus_percentage'] = $this->plan->getRunningMonths()->first()->hygiene_percentage;
            $this->reportData['hygiene']['current_bonus_percentage'] = $this->plan->getRunningMonths()->last()->hygiene_percentage;
            $this->reportData['hygiene']['number_of_percentage_changes'] = $this->getNumberOfPercentageChanges('hygiene');
            $this->reportData['hygiene']['current_streak'] = $this->getCurrentStreak('hygiene');
            $this->reportData['hygiene']['longest_streak'] = $this->getLongestStreak('hygiene');
            $this->reportData['hygiene']['total_hours_worked'] = $this->getTotalHoursWorked('hygiene');
            $this->reportData['hygiene']['total_bonus_amount'] = $this->getTotalBonusAmount('hygiene');
            $this->reportData['hygiene']['bonus_per_hour'] = $this->reportData['hygiene']['total_hours_worked'] == 0 ? 0 : $this->reportData['hygiene']['total_bonus_amount'] / $this->reportData['hygiene']['total_hours_worked'];
        }

        return $this;
    }

    /**
     * Calculates the total bonuses amount paid to Funds
     *
     * @return float
     */
    protected function getTotalPaidToFunds(): float
    {
        $amount = 0;
        // if the plan does not have funds enabled, or no funds are created, return 0
        if (!$this->plan->separate_fund || !$this->plan->funds->count()) {
            return $amount;
        }

        // Considering only finalized months.
        foreach ($this->plan->getRunningMonths() as $month) {
            foreach ($month->funds as $fund) {
                $amount += $fund->amount_received;
            }
        }

        return $amount;
    }

    /**
     * Calculates the total bonuses paid to employees
     * @return float
     */
    protected function getTotalPaid(): float
    {
        $amount = $this->reportData['total_paid_to_funds'];

        // Considering only finalized months.
        foreach ($this->plan->getRunningMonths() as $month) {
            foreach ($month->employeeData as $employee) {
                $amount += $employee->amount_received;
            }
        }

        return $amount;
    }

    /**
     * Calculates the number of months that bonuses were paid
     * @param string $type Employee Types
     * @return int
     */
    protected function getNumberOfMonthsBonusPaid(string $type): int
    {
        $monthsBonusPaid = 0;

        // Considering only finalized months.
        foreach ($this->plan->getRunningMonths() as $month) {
            // loop through all the employees in month
            foreach ($month->employeeData as $employee) {
                // filtering employees per type.
                if (!in_array($employee->user_id, $this->{$type . 'Ids'})) {
                    continue;
                }

                // stop the loop after the an employee with bonus paid.
                if ($employee->amount_received > 0) {
                    $monthsBonusPaid++;
                    break;
                }
            }
        }

        return $monthsBonusPaid;
    }

    /**
     * Calculates the percentage of months were bonuses were paid to employees
     * @param string $type
     * @return float
     */
    protected function getPercentageOfMonthsBonusPaid(string $type): float
    {
        if ($this->reportData[$type]['number_of_months_bonus_paid'] == 0) {
            return 0;
        }

        return ($this->reportData[$type]['number_of_months_bonus_paid'] / $this->reportData['number_of_months']) * 100;
    }

    /**
     * Calculates the number of months that the bonus percentages were changed.
     * @param string $type
     * @return int
     */
    protected function getNumberOfPercentageChanges(string $type): int
    {
        // initial percentage
        $currentPercentage = $this->plan->getRunningMonths()->first()->{$type . '_percentage'};
        $count = 0;

        // Considering only finalized months.
        foreach ($this->plan->getRunningMonths() as $month) {
            // if current percentage is different than the month percentage, increment counter.
            if ($month->{$type . '_percentage'} !== $currentPercentage) {
                $currentPercentage = $month->{$type . '_percentage'};
                $count++;
            }
        }

        return $count;
    }

    /**
     * Calculates the streak of months were bonuses were paid.
     * @param string $type
     * @return int
     */
    protected function getCurrentStreak(string $type): int
    {
        $count = 0;

        // Considering only finalized months.
        foreach ($this->plan->getRunningMonths() as $month) {
            // getting all the employees from a certain type.
            $employees = $month->employeeData->whereIn('user_id', $this->{$type . 'Ids'})->all();

            if (empty($employees)) {
                continue;
            }

            // checking the amount received by the first employee only (if bonuses were paid, all the employees received
            // bonus. If amount was received, increment counter.
            if (current($employees)->amount_received > 0) {
                $count++;
            } else {
                $count = 0;
            }
        }

        return $count;
    }

    /**
     * Calculates longest streak of months were bonuses were paid.
     * @param string $type
     * @return int
     */
    protected function getLongestStreak(string $type): int
    {
        $currentCount = 0;
        $longestCount = 0;

        // Considering only finalized months.
        foreach ($this->plan->getRunningMonths() as $month) {
            // getting all the employees from a certain type.
            $employees = $month->employeeData->whereIn('user_id', $this->{$type . 'Ids'})->all();

            if (empty($employees)) {
                continue;
            }

            // Incrementing current streak if amount received is greater than zero.
            // If equals to zero, reset streak.
            if (current($employees)->amount_received > 0) {
                $currentCount++;
            } else {
                // To reset the current counter, first we need to check if current
                // streak is greater than longest counter recorded. If greater,
                // replace longest counter by current counter.
                if ($currentCount > $longestCount) {
                    $longestCount = $currentCount;
                }
                // resetting current counter.
                $currentCount = 0;
            }
        }

        // This last check is to verify if the current streak is greater than the
        // longest in record. If that's the case, we'll return the current streak,
        // otherwise we'll return the longest streak
        return $currentCount > $longestCount ? $currentCount : $longestCount;
    }

    /**
     * Calculates total hours worked per employee type.
     * @param string $type
     * @return float
     */
    protected function getTotalHoursWorked(string $type): float
    {
        $hours = 0;

        // Considering only finalized months.
        foreach ($this->plan->getRunningMonths() as $month) {
            foreach ($month->employeeData as $employee) {
                if (!in_array($employee->user_id, $this->{$type . 'Ids'})) {
                    continue;
                }

                $hours += $employee->hours_worked;
            }
        }

        return $hours;
    }

    /**
     * Calculates total bonus amount per employee type
     * @param string $type
     * @return float
     */
    protected function getTotalBonusAmount(string $type): float
    {
        $amount = 0;

        // Considering only finalized months.
        foreach ($this->plan->getRunningMonths() as $month) {
            foreach ($month->employeeData as $employee) {
                if (!in_array($employee->user_id, $this->{$type . 'Ids'})) {
                    continue;
                }

                $amount += $employee->amount_received;
            }
        }

        return $amount;
    }
}
