@extends('bonuspro.reports.portrait-wrap')

@section('header')
    <h1>Employee/Fund Summary</h1>
    <span class="planId">Plan ID: <strong>{{ $plan->plan_name }}</strong></span>
    <span>For <strong>{{ $business }}</strong></span>
    <span>From <strong>{{ $startDate }}</strong> to <strong>{{ $endDate }}</strong></span>
    <span>Type: <strong>Summary Report</strong></span>
@stop

@section('content')
    <div class="data">
        <table>
            <thead>
            <tr>
                <th>Employee/Fund</th>
                <th>Gross Pay</th>
                <th>Hrs. Worked</th>
                <th>Bonus Pay</th>
                <th>Gross Pay w/Bonus</th>
            </tr>
            </thead>
            <tbody>
            <?php /* note: nested loop here is for compatibility with the detailed report, which shows a heading for each type */ ?>
            <?php foreach ($reportData as $type => $users): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td>{{ $user['name'] }}</td>
                    <td>${{ number_format($user['gross_pay'], 2, '.', ',') }}</td>
                    <td>{{ number_format($user['hours_worked'], 2, '.', ',') }}</td>
                    <td>${{ number_format($user['amount_received'], 2, '.', ',') }}</td>
                    <td>${{ number_format($user['total_received'], 2, '.', ',') }}</td>
                </tr>
            <?php endforeach; ?>
            <?php endforeach; ?>
            </tbody>
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
