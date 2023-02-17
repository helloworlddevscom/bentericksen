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