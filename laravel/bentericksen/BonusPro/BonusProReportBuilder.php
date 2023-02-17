<?php

namespace Bentericksen\BonusPro;

use App\BonusPro\Plan;
use Bentericksen\BonusPro\Reports\EmployeeFundSummaryReport;
use Bentericksen\BonusPro\Reports\IndividualEmployeeFundSummaryReport;
use Bentericksen\BonusPro\Reports\PlanRecapReport;
use Bentericksen\BonusPro\Reports\PlanSummaryReport;

class BonusProReportBuilder
{
    const REPORT_TYPE = 'reportType';
    const REPORT_NAME = 'reportName';
    const EMP_FUND_SUM = 'employee_fund_summary';
    const IND_EMP_FUND = 'individual_emp_fund';
    const PLAN_SUMMARY = 'plan_summary';
    const PLAN_RECAP = 'plan_recap';

    /**
     * @var array
     */
    private $settings;

    /**
     * @var Plan
     */
    private $plan;

    /**
     * @var string
     */
    private $html;

    /**
     * @var string
     */
    private $orientation;

    /**
     * @var string
     */
    private $paperSize;

    public function __construct(Plan $plan, array $settings)
    {
        $this->plan = $plan;
        $this->settings = $settings;
        $this->paperSize = 'letter'; // If different paper size is needed, you can override it in the "generate" method.

        $this->generate();

        return $this;
    }

    /**
     * Generates Report
     */
    protected function generate()
    {
        $report = null;

        switch ($this->settings[self::REPORT_NAME]) {
            case self::EMP_FUND_SUM:
                $this->orientation = 'portrait';
                $report = new EmployeeFundSummaryReport($this->plan, $this->settings);
                break;
            case self::IND_EMP_FUND:
                $this->orientation = 'portrait';
                $report = new IndividualEmployeeFundSummaryReport($this->plan, $this->settings);
                break;
            case self::PLAN_SUMMARY:
                $this->orientation = 'portrait';
                $report = new PlanSummaryReport($this->plan, $this->settings);
                break;
            case self::PLAN_RECAP:
                $this->orientation = 'portrait';
                $report = new PlanRecapReport($this->plan, $this->settings);
                break;
        }

        if ($report) {
            $this->html = $report->render();
        }
    }

    /**
     * Builds report.
     */
    public function buildReport()
    {
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->html)->setPaper($this->paperSize, $this->orientation);

        return $pdf->stream();
    }
}