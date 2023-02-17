@extends('bonuspro.reports.portrait-wrap')

@section('header')
    <h1>Individual Employee/Fund</h1>
    <span class="planId">Plan ID: <strong>{{ $plan->plan_name }}</strong></span>
    <span>For <strong>{{ $business }}</strong></span>
    <span>From <strong>{{ $startDate }}</strong> to <strong>{{ $endDate }}</strong></span>
@stop

@section('content')
    <?php foreach ($reportData as $row): ?>
    <div class="section">
        <div class="general">
            <span>For: <strong>{{ $row['name'] }}</strong></span>
            <span>Month/Year Employed: <strong>{{ !empty($row['hired']) ? date('m/Y', strtotime($row['hired'])) : '-' }}</strong></span>
            <span>Month/Year Eligible: <strong>{{ !empty($row['bp_eligibility_date']) ? $row['bp_eligibility_date'] : '-' }}</strong></span>
            <span>Month/Year Terminated: <strong>{{ !empty($row['terminated']) ? date('m/Y', strtotime($row['terminated'])) : '-' }}</strong></span>
            <span>Plan Participation: <strong>{{ ucwords($row['bp_employee_type'], " \t\r\n\f\v/") }}</strong></span>
        </div>
        <div class="data">
            <table>
                <thead>
                <tr>
                    <th>Month</th>
                    <th>Gross Pay</th>
                    <th>Hrs. Worked</th>
                    <th>Pay Per/Hr.</th>
                    <th>Bonus Pay</th>
                    <th>Bonus Pay Per/Hr.</th>
                    <th>Gross Pay w/Bonus</th>
                    <th>Total Per/Hr.</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($row['months']))
                    <?php foreach ($row['months'] as $month): ?>
                    <tr>
                        <td>{{ str_pad($month['month'], 2, 0, STR_PAD_LEFT) }}/{{ $month['year'] }}</td>
                        <td>{{ !empty($month['gross_pay']) ? '$' . number_format($month['gross_pay'], 2, '.', ',') : '-' }}</td>
                        <td>{{ !empty($month['hours_worked']) ? $month['hours_worked'] : '-' }}</td>
                        <td>{{ !empty($month['hours_worked']) ? '$' . ($month['hours_worked'] > 0 ? number_format(($month['gross_pay']/$month['hours_worked'] ), 2, '.', ',') : 0.00) : '-' }}</td>
                        <td>${{ number_format($month['amount_received'] , 2, '.', ',') }}</td>
                        <td>{{ !empty($month['hours_worked']) ? '$' . ($month['hours_worked'] > 0 ? number_format(($month['amount_received']/$month['hours_worked'] ), 2, '.', ',') : 0.00) : '-' }}</td>
                        <td>${{ !empty($month['gross_pay']) ? number_format(($month['gross_pay'] + $month['amount_received'] ), 2, '.', ',') : number_format($month['amount_received'], 2, '.', ',') }}</td>
                        <td>{{ !empty($month['hours_worked']) ? '$' . ($month['hours_worked'] > 0 ? number_format((($month['gross_pay'] + $month['amount_received'] )/$month['hours_worked'] ), 2, '.', ',') : 0.00) : '-' }}</td>
                    </tr>
                    <?php endforeach; ?>
                @endif
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td class="totalColumn">{{ !empty($row['totalGrossPay']) ? '$' . number_format($row['totalGrossPay'], 2, '.', ',') : '-' }}</td>
                    <td class="totalColumn">{{ !empty($row['totalHoursWorked']) ? number_format($row['totalHoursWorked'], 2) : '-' }}</td>
                    <td></td>
                    <td class="totalColumn">${{ number_format($row['totalAmountReceived'], 2, '.', ',') }}</td>
                    <td></td>
                    <td class="totalColumn">${{ number_format($row['grandTotalReceived'], 2, '.', ',') }}</td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php endforeach; ?>
    <script type="text/php">
    if (isset($pdf)) {
        $date = date('m/d/Y', time());
        $font = $fontMetrics->getFont("helvetica", "bold");
        $pdf->page_text(535, 30, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 8, array(0,0,0));
        $pdf->page_text(535, 40, $date, $font, 8, array(0,0,0));
    }
    </script>
@stop
