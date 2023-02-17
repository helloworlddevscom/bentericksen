<?php

namespace Tests\Unit;

use App\BonusPro\Month;
use App\BonusPro\Plan;
use Bentericksen\BonusPro\Reports\EmployeeFundSummaryReport;
use Tests\TestCase;

/**
 * Class ReportTest
 * @package Tests\Unit
 * @group bonusPro
 */
class ReportTest extends TestCase
{
    protected static $report1;
    protected static $report2;
    protected static $report3;

    /**
     * @group bonusPro
     */
    protected function setUp(): void
    {
        parent::setUp();

        $plan = new Plan;
        // mock empty users array is ok for now; this prevents it trying to
        // access the db until we have fixtures in place
        $plan->users = [];

        $settings1 = [
            'startDateMonth' => 1,
            'startDateYear' => 2018,
            'endDateMonth' => 6,
            'endDateYear' => 2018,
            'singleEmployeeFund' => false,
            'employeesFunds' => 'all',
        ];
        self::$report1 = new EmployeeFundSummaryReport($plan, $settings1);

        $settings2 = [
            'startDateMonth' => 10,
            'startDateYear' => 2017,
            'endDateMonth' => 3,
            'endDateYear' => 2018,
            'singleEmployeeFund' => false,
            'employeesFunds' => 'staff_employees',
        ];
        self::$report2 = new EmployeeFundSummaryReport($plan, $settings2);

        $settings3 = [
            'startDateMonth' => 10,
            'startDateYear' => 2017,
            'endDateMonth' => 3,
            'endDateYear' => 2018,
            'singleEmployeeFund' => false,
            'employeesFunds' => 'hygiene_employees',
        ];
        self::$report3 = new EmployeeFundSummaryReport($plan, $settings3);
    }

    /**
     * Tests whether a month should be in the report.
     *
     * @return void
     * @throws \Exception
     * @group bonusPro
     */
    public function testMonthInReport()
    {
        $month1 = new Month;
        $month1->month = 4;
        $month1->year = 2018;
        $month1->finalized = true;

        $month2 = new Month;
        $month2->month = 12;
        $month2->year = 2017;
        $month2->finalized = true;

        $month3 = new Month;
        $month3->month = 12;
        $month3->year = 2018;
        $month3->finalized = true;

        $month4 = new Month;
        $month4->month = 1;
        $month4->year = 2018;
        $month4->finalized = false;

        // report1 is january-june 2018 (i.e., all in one year)
        $this->assertTrue(self::$report1->inReport($month1));
        $this->assertFalse(self::$report1->inReport($month2));
        $this->assertFalse(self::$report1->inReport($month3));
        $this->assertFalse(self::$report1->inReport($month4));

        // report2 starts and ends in a different year (2017-10 to 2018-03)
        $this->assertFalse(self::$report2->inReport($month1));
        $this->assertTrue(self::$report2->inReport($month2));
        $this->assertFalse(self::$report2->inReport($month3));
        $this->assertFalse(self::$report2->inReport($month4));
    }

    /**
     * Tests the logic for determining if a user should appear in a given report.
     *
     * @return void
     * @throws \Exception
     * @group bonusPro
     */
    public function testUserIsDesiredType()
    {
        $staff_user = new \App\User;
        $staff_user->bp_employee_type = 'admin/assistant';

        $hygiene_user = new \App\User;
        $hygiene_user->bp_employee_type = 'hygienist';

        // report1 is "all"
        $this->assertTrue(self::$report1->userIsDesiredType($staff_user));
        $this->assertTrue(self::$report1->userIsDesiredType($hygiene_user));

        // report2 is "staff"
        $this->assertTrue(self::$report2->userIsDesiredType($staff_user));
        $this->assertFalse(self::$report2->userIsDesiredType($hygiene_user));

        // report3 is "hygiene"
        $this->assertFalse(self::$report3->userIsDesiredType($staff_user));
        $this->assertTrue(self::$report3->userIsDesiredType($hygiene_user));
    }
}
