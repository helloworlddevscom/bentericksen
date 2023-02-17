@extends('bonuspro.reports.portrait-wrap')

@section('header')
    <h1>Individual Employee/Fund</h1>
    <span class="planId">Plan ID: <strong>{{ $plan->plan_name }}</strong></span>
    <span>For <strong>{{ $business }}</strong></span>
    <span>From <strong>{{ $startDate }}</strong> to <strong>{{ $endDate }}</strong></span>
@stop

@section('content')
    <?php foreach ($reportData as $user): ?>
    <div class="section">
        <div class="general">
            <span>For: <strong>{{ $user['name'] }}</strong></span>
            <span>Month/Year Employed: <strong>{{ !empty($user['hired']) ? date('m/Y', strtotime($user['hired'])) : '-' }}</strong></span>
            <span>Month/Year Eligible: <strong>{{ !empty($user['bp_eligibility_date']) ? $user['bp_eligibility_date'] : '-' }}</strong></span>
            <span>Month/Year Terminated: <strong>{{ !empty($user['terminated']) ? date('m/Y', strtotime($user['terminated'])) : '-' }}</strong></span>
            <span>Plan Participation: <strong>{{ ucwords($user['bp_employee_type'], " \t\r\n\f\v/") }}</strong></span>
        </div>
        <div class="data">
            <table>
                <thead>
                <tr>
                    <th>Gross Pay</th>
                    <th>Hrs. Worked</th>
                    <th>Bonus Pay</th>
                    <th>Gross Pay w/Bonus</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ isset($user['totalGrossPay']) ? '$' . number_format($user['totalGrossPay'], 2, '.', ',') : '-' }}</td>
                    <td>{{ isset($user['totalHoursWorked']) ? number_format($user['totalHoursWorked'], 2) : '-' }}</td>
                    <td>${{ number_format($user['totalAmountReceived'], 2, '.', ',') }}</td>
                    <td>${{ number_format($user['grandTotalReceived'], 2, '.', ',') }}</td>
                </tr>
                </tbody>
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
