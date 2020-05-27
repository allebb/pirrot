<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('html-title')</title>
    <link rel="stylesheet" href="{{ url('/css/bulma.min.css') }}">
    <link rel="stylesheet" href="{{ url('/css/custom.css') }}">
    @yield('css')
</head>
<body>
<section class="section">
    <div class="container">
        <div class="columns is-mobile">
            <div class="column is-one-quarter">
                <aside class="menu">

                    <a href="{{ route('dashboard') }}"><img src="/img/pirrot_logo.png" title="Pirrot - Open-source Repeater Controller Software"></a>

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
                        <li><a href="{{ route('support') }}">Help & About</a></li>
                    </ul>
                </aside>
            </div>
            <div class="column">
                <h1 class="title">@yield('title')</h1>
                @yield('content')
            </div>
        </div>

        <footer class="footer">
            <div class="content has-text-centered">
                <p>
                    Powered by <strong><a href="https://pirrot.hallinet.com/" target="_blank">Pirrot</a></strong> an open-source repeater controller system.
                </p>
                <p>
                    <a href="https://github.com/allebb/pirrot" target="_blank"><img src="/img/pirrot_footer.png" alt="Pirrot Footer Logo" title="Pirrot - Open-source Repeater Controller Software"></a>
                </p>
            </div>
        </footer>

    </div>
</section>
<script src="{{ url('/js/jquery.min.js') }}"></script>
@yield('js')
</body>
</html>
