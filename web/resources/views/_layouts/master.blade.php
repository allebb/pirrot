<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('html-title')</title>
    <link rel="stylesheet" href="{{ url('/css/bulma.min.css') }}">
    @yield('css')
</head>
<body>
<section class="section">
    <div class="container">
        <div class="columns is-mobile">
            <div class="column is-one-quarter">
                <aside class="menu">
                    <p class="menu-label">
                        General
                    </p>
                    <ul class="menu-list">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('recordings') }}">Audio Recordings</a></li>
                        <li><a href="{{ route('weather-reports') }}">Weather Reports</a></li>
                    </ul>
                    <p class="menu-label">
                        Administration
                    </p>
                    <ul class="menu-list">
                        <li><a href="{{ route('settings') }}">Settings</a></li>
                    </ul>
                    <p class="menu-label">
                        Other
                    </p>
                    <ul class="menu-list">
                        <li><a href="{{ route('support') }}">Help & Support</a></li>
                    </ul>
                </aside>
            </div>
            <div class="column">
                <h1 class="title">@yield('title')</h1>
                @yield('content')
            </div>
        </div>

    </div>
</section>
<script src="{{ url('/js/jquery.min.js') }}"></script>
@yield('js')
</body>
</html>
