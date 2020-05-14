@extends('_layouts.master')

@section('html-title', 'Dashboard - Pirrot Web Interface')
@section('title', 'Dashboard')

@section('content')
    <h2 class="subtitle">System statistics</h2>

    <div class="row">
        <div class="column is-half">

            <article class="message">
                <div class="message-header">
                    <p>System Information</p>
                </div>
                <div class="table-container table-responsive">
                    <table class="table has-background-light">
                        <tr>
                            <th>Hostname</th>
                            <td id="s_hostname"><span class="has-text-grey-light">Loading</span></td>
                        </tr>
                        <tr>
                            <th>Uptime</th>
                            <td id="s_uptime"><span class="has-text-grey-light">Loading</span></td>
                        </tr>
                        <tr>
                            <th>System Time</th>
                            <td id="s_uptime"><span class="has-text-grey-light">Loading</span></td>
                        </tr>
                        <tr>
                            <th>Hardware Model</th>
                            <td id="s_model"><span class="has-text-grey-light">Loading</span></td>
                        </tr>
                        <tr>
                            <th>OS Version</th>
                            <td id="s_ver_os"><span class="has-text-grey-light">Loading</span></td>
                        </tr>
                        <tr>
                            <th>Pirrot Version</th>
                            <td id="s_ver_pirrot"><span class="has-text-grey-light">Loading</span></td>
                        </tr>
                    </table>
                </div>
            </article>
            <article class="message">
                <div class="message-header">
                    <p>System Resources</p>
                </div>
                <div class="table-container">
                    <table class="table has-background-light">
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
                            <td id="s_ram"><span class="has-text-grey-light">Loading</span></td>
                        </tr>
                        <tr>
                            <th>Temperature</th>
                            <td id="s_temp"><span class="has-text-grey-light">Loading</span></td>
                        </tr>
                    </table>
                </div>
            </article>
        </div>



        <div class="column is-half">
            <article class="message">
                <div class="message-header">
                    <p>GPS</p>
                </div>
                <div class="message-body">
                    No GPS receiver detected!
                </div>
            </article>
        </div>


    </div>

@endsection

@section('js')
    <script>
        console.log('got here!');
    </script>
@endsection
