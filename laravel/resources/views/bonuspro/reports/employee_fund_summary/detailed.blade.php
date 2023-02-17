@extends('bonuspro.reports.portrait-wrap')

@section('header')
    <h1>Employee/Fund Summary</h1>
    <span class="planId">Plan ID: <strong>{{ $plan->plan_name }}</strong></span>
    <span>For <strong>{{ $business }}</strong></span>
    <span>From <strong>{{ $startDate }}</strong> to <strong>{{ $endDate }}</strong></span>
    <span>Type: <strong>Detailed Report</strong></span>
@stop

@section('content')
    <div class="data">
        <table>
            <thead>
            <tr>
                <th>Employee/Fund</th>
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
            <?php
            $grandTotalGrossPay = 0;
            $grandTotalHoursWorked = 0;
            $grandTotalAmountReceived = 0;
            $grandTotalGrandTotalReceived = 0;
            ?>
            <?php foreach ($reportData as $type => $rows): ?>
            <?php
            $planTotalGrossPay = 0;
            $planTotalHoursWorked = 0;
            $planTotalAmountReceived = 0;
            $planTotalGrandTotalReceived = 0;
            ?>
            <?php if (count($rows) > 0): ?>
            <tr class="grandTotalsRow">
                <td colspan="8"><strong>{{ ucwords($type, " \t\r\n\f\v/") }}</strong></td>
            </tr>
            <?php foreach ($rows as $row): ?>
                <?php
                $planTotalGrossPay += $row['gross_pay'];
                $planTotalHoursWorked += $row['hours_worked'];
                $planTotalAmountReceived += $row['amount_received'];
                $planTotalGrandTotalReceived += $row['total_received'];
                ?>
                <tr>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ isset($row['gross_pay']) ? '$' . number_format($row['gross_pay'], 2, '.', ',') : '-' }}</td>
                    <td>{{ isset($row['hours_worked']) ? number_format($row['hours_worked'], 2, '.', ',') : '-' }}</td>
                    <td>{{ isset($row['hours_worked']) ? '$' . ($row['hours_worked'] > 0  ? number_format(($row['gross_pay']/$row['hours_worked']), 2, '.', ',') : 0.00) : '-' }}</td>
                    <td>${{ number_format($row['amount_received'], 2, '.', ',') }}</td>
                    <td>{{ isset($row['hours_worked']) ? '$' . ($row['hours_worked'] > 0 ? number_format(($row['amount_received']/$row['hours_worked']), 2, '.', ',') : 0.00) : '-' }}</td>
                    <td>${{ number_format($row['total_received'], 2, '.', ',') }}</td>
                    <td>{{ isset($row['hours_worked']) ? '$' . ($row['hours_worked'] > 0 ? number_format(($row['total_received']/$row['hours_worked']), 2, '.', ',') : 0.00) : '-' }}</td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td>Total ({{ ucwords($type, " \t\r\n\f\v/") }})</td>
                <td class="totalColumn">${{ number_format($planTotalGrossPay, 2, '.', ',') }}</td>
                <td class="totalColumn">{{ number_format($planTotalHoursWorked, 2, '.', ',') }}</td>
                <td></td>
                <td class="totalColumn">${{ number_format($planTotalAmountReceived, 2, '.', ',') }}</td>
                <td></td>
                <td class="totalColumn">${{ number_format($planTotalGrandTotalReceived, 2, '.', ',') }}</td>
                <td></td>
            </tr>
            <?php endif; ?>
            <?php
            $grandTotalGrossPay += $planTotalGrossPay;
            $grandTotalHoursWorked += $planTotalHoursWorked;
            $grandTotalAmountReceived += $planTotalAmountReceived;
            $grandTotalGrandTotalReceived += $planTotalGrandTotalReceived;
            ?>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr class="grandTotalsRow">
                <td>Report Total</td>
                <td>${{ number_format($grandTotalGrossPay, 2, '.', ',') }}</td>
                <td>{{ number_format($grandTotalHoursWorked, 2, '.', ',') }}</td>
                <td></td>
                <td>${{ number_format($grandTotalAmountReceived, 2, '.', ',') }}</td>
                <td></td>
                <td>${{ number_format($grandTotalGrandTotalReceived, 2, '.', ',') }}</td>
                <td></td>
            </tr>
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
