@extends('_layouts.master')

@section('html-title', 'Dashboard - Pirrot Web Interface')
@section('title', 'Dashboard')

@section('content')
    <h2 class="subtitle">System statistics</h2>

    <div class="columns">
        <div class="column is-half">
            <article class="message">
                <div class="message-header">
                    <p>System Information</p>
                </div>
                <table class="table has-background-light is-fullwidth">
                    <tr>
                        <th>Hostname</th>
                        <td><span class="has-text-grey-darker">{{ $system->hostname }}</span></td>
                    </tr>
                    <tr>
                        <th>Model</th>
                        <td><span class="has-text-grey-darker">{{ $system->hardware_model }}</span></td>
                    </tr>
                    <tr>
                        <th>Serial #</th>
                        <td><span class="has-text-grey-darker">{{ $system->hardware_serial }}</span></td>
                    </tr>
                    <tr>
                        <th>Processor</th>
                        <td><span class="has-text-grey-darker"><abbr
                                    title="CPU Cores">{{ $system->hardware_cpu_count}} core(s) </abbr> at {{ number_format(($system->hardware_cpu_freq / 1000),1,'.','') }} GHz ({{ $system->hardware_cpu_arch }})</span>
                        </td>
                    </tr>
                    <tr>
                        <th>OS</th>
                        <td><span class="has-text-grey-darker">{{ $system->version_raspbian }}</span></td>
                    </tr>
                    <tr>
                        <th>Kernel</th>
                        <td><span class="has-text-grey-darker">{{ $system->version_kernel }}</span></td>
                    </tr>
                    <tr>
                        <th>Pirrot</th>
                        <td><span class="has-text-grey-darker">v{{ $system->version_pirrot }}</span></td>
                    </tr>
                </table>
            </article>

        </div>

        <div class="column is-half-desktop">
            <article class="message">
                <div class="message-header">
                    <p>Resources</p>
                </div>
                <table class="table is-fullwidth has-background-light">
                    <tr>
                        <th>Uptime</th>
                        <td id="s_uptime"><span class="has-text-grey-light">Loading</span></td>
                    </tr>
                    <tr>
                        <th>System Time</th>
                        <td id="s_systime"><span class="has-text-grey-light">Loading</span></td>
                    </tr>
                    <tr>
                        <th>CPU</th>
                        <td id="s_cpu"><span class="has-text-grey-light">Loading</span></td>
                    </tr>
                    <tr>
                        <th>Memory</th>
                        <td id="s_ram"><span class="has-text-grey-light">Loading</span></td>
                    </tr>
                    <tr>
                        <th>Disk</th>
                        <td id="s_disk"><span class="has-text-grey-light">Loading</span></td>
                    </tr>
                    <tr>
                        <th>Temperature</th>
                        <td id="s_temp"><span class="has-text-grey-light">Loading</span></td>
                    </tr>
                </table>
            </article>

            <article class="message">
                @if(!$system->gps_configured)
                    <div class="message-header has-background-grey-light">
                        <p>GPS</p>
                    </div>
                    <div class="message-body">
                        No GPS receiver detected!
                    </div>
                @else
                    <div class="message-header">
                        <p>GPS</p>
                    </div>
                    <table class="table is-fullwidth has-background-light">
                        <tr>
                            <th>Latitude</th>
                            <td id="s_lat"><span class="has-text-grey-light">Loading</span></td>
                        </tr>
                        <tr>
                            <th>Longitude</th>
                            <td id="s_lng"><span class="has-text-grey-light">Loading</span></td>
                        </tr>
                        <tr>
                            <th>Altitude</th>
                            <td id="s_alt"><span class="has-text-grey-light">Loading</span></td>
                        </tr>
                    </table>
                @endif
            </article>

        </div>
    </div>

@endsection

@section('js')
    <script>
        console.log('got here!');
    </script>
@endsection
