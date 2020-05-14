@extends('_layouts.master')


@section('html-title', 'Settings - Pirrot Web Interface')
@section('title', 'Settings')

@section('content')
    <h2 class="subtitle">Review and update your repeater settings</h2>

    <form method="POST" action="{{ route('settings') }}">
        <article class="message">
            <div class="message-header">
                <p>Settings</p>
            </div>
            <div class="message-body">

                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label">From</label>

                    </div>
                    <div class="field-body">
                        <div class="field">
                            <p>This is some text that will apepar and explain what this field is for etc. etc.</p>
                            <p class="control">
                                <input class="input is-static has-background-white" type="email" value="me@example.com">
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </article>
        <button type="submit" class="button is-success is-outlined">Save and apply changes</button>
    </form>
@endsection


@section('js')
    <script>
        console.log('got here!');
    </script>
@endsection
