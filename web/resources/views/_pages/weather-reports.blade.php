@extends('_layouts.master')


@section('html-title', 'Weather Reports - Pirrot Web Interface')
@section('title', 'Weather Reports')

@section('content')
    <h2 class="subtitle">Recent weather reports</h2>

    @if(!app('pirrot.config')->owm_enabled)
        <article class="message is-danger">
            <div class="message-body">
                Your settings are currently set to not broadcast weather reports, if you want to enable this feature you
                must first enable the <strong>owm_enabled</strong>
                option in your configuration.
            </div>
        </article>
    @endif

    <p style="padding-bottom: 1.6rem;">You can configure your weather reports from the <a
            href="{{ route('settings') }}">Settings</a> page.</p>

    @if($reports->count() > 0)
        <article class="message pt-5">
            <div class="message-header">
                <p>Weather Reports</p>
            </div>
            <table class="message-body table is-striped is-fullwidth">
                <thead>
                <tr>
                    <th>Description</th>
                    <th>Temperature</th>
                    <th>Winds</th>
                    <th>Pressure</th>
                    <th>Humidity</th>
                    <th>Locale</th>
                    <th>Reported</th>
                    <th>Broadcast</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td nowrap>{{ $report->description }}</td>
                        <td><small>{{ $report->temp_c }}&deg;C / {{ $report->temp_f }}&deg;F</small></td>
                        <td><small><abbr title="{{ $report->wind_dir_hdg }}&deg;">{{ $report->wind_dir_crd }}</abbr>
                                at {{ $report->wind_mph }}mph / {{ $report->wind_kph }}kph</small>
                        </td>
                        <td>{{ $report->pressure }} hPa</td>
                        <td>{{ $report->humidity }}%</td>
                        <td><a href="{{ $report->map_view_url }}" target="_blank">{{ $report->lat }}
                                ,{{ $report->lon }}</a>
                        </td>
                        <td><small>{{ $report->reported_at }}</small></td>
                        <td><small>{{ $report->broadcast_at }}</small></td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Description</th>
                    <th>Temperature</th>
                    <th>Winds</th>
                    <th>Pressure</th>
                    <th>Humidity</th>
                    <th>Locale</th>
                    <th>Reported</th>
                    <th>Broadcast</th>
                </tr>
                </tfoot>
            </table>
        </article>
    @else
        <article class="message pt-5">
            <div class="message-header">
                <p>Weather Reports</p>
            </div>
            <div class="message-body">
                <p style="padding: 4rem; text-align: center;">No reports found.</p>
            </div>
        </article>
    @endif
@endsection


@section('js')
    <script>
    </script>
@endsection
