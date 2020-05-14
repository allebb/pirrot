@extends('_layouts.master')


@section('html-title', 'Weather Reports - Pirrot Web Interface')
@section('title', 'Weather Reports')

@section('content')
    <h2 class="subtitle">Auto-broadcasting of weather reports</h2>

    <article class="message is-danger">
        <div class="message-body">
            Your settings are currently set to not auto-transmit weather reports, if you want to enable this feature you
            must first enable the <strong>transmit_wx_reports</strong>
            option in your configuration.
        </div>
    </article>

    <p style="padding-bottom: 1.6rem;">You can configure your weather reports from the <a href="{{ route('settings') }}">Settings</a> page.</p>

    <article class="message pt-5">
        <div class="message-header">
            <p>Recent Weather Reports</p>
        </div>
        <div class="message-body">
            Output the settings stuff here!
        </div>
    </article>
@endsection


@section('js')
    <script>
        console.log('got here!');
    </script>
@endsection
