<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ url('/css/bulma.min.css') }}">
</head>
<body>
<section class="section">
    <div class="container">
        @yield('content')
    </div>
</section>
<script src="{{ url('/js/jquery.min.js') }}"></script>
</body>
</html>
