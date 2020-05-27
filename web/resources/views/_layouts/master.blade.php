<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('html-title')</title>
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/icons/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/icons/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/icons/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/icons/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="/icons/apple-touch-icon-60x60.png" />
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="/icons/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="/icons/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="/icons/apple-touch-icon-152x152.png" />
    <link rel="icon" type="image/png" href="/icons/favicon-196x196.png" sizes="196x196" />
    <link rel="icon" type="image/png" href="/icons/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/png" href="/icons/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="/icons/favicon-16x16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="/icons/favicon-128.png" sizes="128x128" />
    <meta name="application-name" content="Pirrot Repeater Web Interface"/>
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta name="msapplication-TileImage" content="/icons/mstile-144x144.png" />
    <meta name="msapplication-square70x70logo" content="/icons/mstile-70x70.png" />
    <meta name="msapplication-square150x150logo" content="/icons/mstile-150x150.png" />
    <meta name="msapplication-wide310x150logo" content="/icons/mstile-310x150.png" />
    <meta name="msapplication-square310x310logo" content="/icons/mstile-310x310.png" />
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
