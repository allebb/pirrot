@extends('_layouts.master')


@section('html-title', 'Help & About - Pirrot Web Interface')
@section('title', 'Help & About')

@section('content')
    <h2 class="subtitle">Getting help with your Pirrot based repeater</h2>

    <h2 class="is-size-4">About Pirrot</h2>
    <p style="margin-bottom: 0.5rem;">Pirrot is an open-source repeater controller software solution developed and lead
        by
        <a href="https://bobbyallen.me" target="_blank">Bobby Allen</a> with contribution by <a
            href="https://github.com/allebb/pirrot/graphs/contributors" target="_blank">others</a>.</p>
    <p style="margin-bottom: 2rem;">Pirrot is released under the <a
            href="https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html" target="_blank">GPLv2</a> license.</p>

    <h2 class="is-size-4">Help and Documentation</h2>
    <p style="margin-bottom: 0.5rem;">
        Documentation and useful information can be found on the <a href="https://pirrot.hallinet.com/"
                                                                    target="_blank">Pirrot project website</a>.
    </p>
    <p style="margin-bottom: 2rem;">
        If you have a specific issue or question please email: <a
            href="mailto:ballen@bobbyallen.me?subject=Pirrot%20Support%20Request">ballen@bobbyallen.me</a>
    </p>
    <h2 class="is-size-4">Raising issues</h2>
    <p style="margin-bottom: 1rem;">
        If you believe that you have found a bug or other software related issue with the software please raise a new
        issue on the bug tracking system.
    </p>
    <p>
        <a href="https://github.com/allebb/pirrot/issues" target="_blank" class="button">Open an issue on GitHub</a>
    </p>

@endsection

@section('js')
@endsection
