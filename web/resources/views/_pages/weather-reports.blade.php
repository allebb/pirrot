@extends('_layouts.master')


@section('html-title', 'Weather Reports - Pirrot Web Interface')
@section('title', 'Weather Reports')

@section('content')
    <h2 class="subtitle">Auto-broadcasting of weather reports</h2>

    <p>This is where we will render out the Pirrot settings.</p>
@endsection


@section('js')
    <script>
        console.log('got here!');
    </script>
@endsection
