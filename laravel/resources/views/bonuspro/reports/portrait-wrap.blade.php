<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <style>
        @page {
            margin: 110px 40px 40px;
            font-family: Helvetica, Arial, Sans-Serif;
            font-size: 10px;
            line-height: 1.4;
        }

        body {
        }

        header {
            position: fixed;
            top: -110px;
            left: 0px;
            right: 0px;
            height: 100px;
            text-align: center;
            border-bottom: 2px solid forestgreen;
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 24px;
            line-height: 1;
            color: forestgreen;
            margin: 30px 0 0 0;
        }

        span {
            display: block;
            clear: both;
        }

        .planId {
            position: absolute;
            left: 0;
            top: 38px;
        }

        footer {
            position: fixed;
            left: 0px;
            right: 0px;
            bottom: 0px;
            height: 40px;
            line-height: 55px;
            text-align: center;
        }

        main {
        }

        .section {
            border-top: 2px solid forestgreen;
            border-bottom: 2px solid forestgreen;
            margin-bottom: 8px;
            width: 100%;
            padding: 10px 0;
            display: block;
        }

        .data {
            padding: 30px 0;
        }

        table {
            width: 100%;
        }

        thead {
            border-bottom: 2px solid forestgreen;
        }

        .totalColumn {
            border-top: 2px solid forestgreen;
        }

        .bpReportSeparator {
            border-bottom: 2px solid forestgreen;
            padding:20px 0 5px;
            font-size: 20px;
        }

        .separator + tr:first-child > td {
            padding-top: 10px;
        }

        .grandTotalsRow td {
            font-weight: bold;
            padding: 20px 0 0;
        }
    </style>
</head>
<body>
<header>
    @yield('header')
</header>
<footer>
    @yield('footer')
</footer>
<main class="content">
    @yield('content')
</main>
</body>
</html>