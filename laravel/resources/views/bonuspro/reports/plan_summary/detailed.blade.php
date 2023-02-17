@extends('bonuspro.reports.portrait-wrap')

@section('header')
    <h1>Plan Summary</h1>
    <span class="planId">Plan ID: <strong>{{ $plan->plan_name }}</strong></span>
    <span>For <strong>{{ $business }}</strong></span>
    <span>From <strong>{{ $startDate }}</strong> to <strong>{{ $endDate }}</strong></span>
@stop

@section('content')
    <div class="data">
        <table>
            <thead>
            <tr>
                <th>Date</th>
                <th>Production</th>
                <th>Collection</th>
                <th>P+C Avg.</th>
                <th>Bonus %</th>
                <th>Staff Salaries</th>
                <th>Staff Hrs.</th>
                <th>Hygienists Salaries</th>
                <th>Hygienists Hrs.</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($reportData as $row)
                <tr>
                    <td>{{ $row['date'] }}</td>
                    <td>{{ $row['production_amount'] }}</td>
                    <td>{{ $row['collection_amount'] }}</td>
                    <td>{{ $row['production_collection_average'] }}</td>
                    <td>{{ $row['staff_percentage'] }}</td>
                    <td>{{ $row['staff_salaries'] }}</td>
                    <td>{{ $row['staff_hours'] }}</td>
                    <td>{{ $row['hygienists_salaries'] }}</td>
                    <td>{{ $row['hygienists_hours'] }}</td>
                </tr>
            @endforeach
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