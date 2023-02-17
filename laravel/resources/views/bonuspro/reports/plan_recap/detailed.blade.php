@extends('bonuspro.reports.portrait-wrap')

@section('header')
    <h1>Plan Recap</h1>
    <span class="planId">Plan ID: <strong>{{ $plan->plan_name }}</strong></span>
    <span>For <strong>{{ $business }}</strong></span>
@stop

@section('content')
    <div class="data">
        <table>
            <tbody>
                <tr>
                    <td>Plan Setup (Month/Year)</td>
                    <td>{{ $reportData['plan_setup_date'] }}</td>
                </tr>
                <tr>
                    <td>Number of Months Plan Active</td>
                    <td>{{ $reportData['number_of_months'] }}</td>
                </tr>
                <tr>
                    <td>Total Amount Paid To Funds</td>
                    <td>${{ number_format($reportData['total_paid_to_funds'], 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td>Total Amount Paid</td>
                    <td>${{ number_format($reportData['total_paid'], 2, '.', ',') }}</td>
                </tr>
                <tr class="separator">
                    <td colspan="2" class="bpReportSeparator"><strong>Staff Recap</strong></td>
                </tr>
                <tr>
                    <td>Number of Months Bonus Made</td>
                    <td>{{ $reportData['staff']['number_of_months_bonus_paid'] }}</td>
                </tr>
                <tr>
                    <td>Percentage of Months Bonus Made</td>
                    <td>{{ number_format($reportData['staff']['percentage_of_months_bonus_paid'], 2) }}%</td>
                </tr>
                <tr>
                    <td>Original Bonus Percentage</td>
                    <td>{{ number_format($reportData['staff']['original_bonus_percentage'], 2) }}%</td>
                </tr>
                <tr>
                    <td>Current Bonus Percentage</td>
                    <td>{{ number_format($reportData['staff']['current_bonus_percentage'], 2) }}%</td>
                </tr>
                <tr>
                    <td>Number of Bonus Percentage Changes</td>
                    <td>{{ $reportData['staff']['number_of_percentage_changes'] }}</td>
                </tr>
                <tr>
                    <td>Current Bonus Streak (Months)</td>
                    <td>{{ $reportData['staff']['current_streak'] }}</td>
                </tr>
                <tr>
                    <td>Longest Bonus Streak (Months)</td>
                    <td>{{ $reportData['staff']['longest_streak'] }}</td>
                </tr>
                <tr>
                    <td>Total Hours Worked</td>
                    <td>{{ number_format($reportData['staff']['total_hours_worked'], 2) }}</td>
                </tr>
                <tr>
                    <td>Total Amount of Bonuses</td>
                    <td>${{ number_format($reportData['staff']['total_bonus_amount'], 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td>Bonus Dollars Per Hour Worked</td>
                    <td>{{ number_format($reportData['staff']['bonus_per_hour'], 2) }}</td>
                </tr>
                @isset($reportData['hygiene'])
                <tr class="separator">
                    <td colspan="2" class="bpReportSeparator"><strong>Hygiene Recap</strong></td>
                </tr>
                <tr>
                    <td>Number of Months Bonus Made</td>
                    <td>{{ $reportData['hygiene']['number_of_months_bonus_paid'] }}</td>
                </tr>
                <tr>
                    <td>Percentage of Months Bonus Made</td>
                    <td>{{ number_format($reportData['hygiene']['percentage_of_months_bonus_paid'], 2) }}%</td>
                </tr>
                <tr>
                    <td>Original Bonus Percentage</td>
                    <td>{{ number_format($reportData['hygiene']['original_bonus_percentage'], 2) }}%</td>
                </tr>
                <tr>
                    <td>Current Bonus Percentage</td>
                    <td>{{ number_format($reportData['hygiene']['current_bonus_percentage'], 2) }}%</td>
                </tr>
                <tr>
                    <td>Number of Bonus Percentage Changes</td>
                    <td>{{ $reportData['hygiene']['number_of_percentage_changes'] }}</td>
                </tr>
                <tr>
                    <td>Current Bonus Streak (Months)</td>
                    <td>{{ $reportData['hygiene']['current_streak'] }}</td>
                </tr>
                <tr>
                    <td>Longest Bonus Streak (Months)</td>
                    <td>{{ $reportData['hygiene']['longest_streak'] }}</td>
                </tr>
                <tr>
                    <td>Total Hours Worked</td>
                    <td>{{ number_format($reportData['hygiene']['total_hours_worked'], 2) }}</td>
                </tr>
                <tr>
                    <td>Total Amount of Bonuses</td>
                    <td>${{ number_format($reportData['hygiene']['total_bonus_amount'], 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td>Bonus Dollars Per Hour Worked</td>
                    <td>{{ number_format($reportData['hygiene']['bonus_per_hour'], 2) }}</td>
                </tr>
                @endisset
            </tbody>
            <tfoot>
            </tfoot>
        </table>
    </div>
    <script type="text/php">
    if (isset($pdf)) {
        $date = date('m/d/Y', time());
        $font = $fontMetrics->getFont("helvetica", "bold");
        $pdf->page_text(535, 30, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 8, array(0,0,0));
        $pdf->page_text(535, 40, $date, $font, 8, array(0,0,0));
    }
    </script>
@stop