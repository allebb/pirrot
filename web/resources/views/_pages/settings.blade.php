@extends('_layouts.master')


@section('html-title', 'Settings - Pirrot Web Interface')
@section('title', 'Settings')

@section('content')
    <h2 class="subtitle">Review and update your repeater settings</h2>

    <form method="POST" action="{{ route('settings') }}">


        @foreach($panels as $group => $config)
            <article class="message">
                <div class="message-header">
                    <p>{{ $group }}</p>
                </div>

                <div class="message-body">
                    @foreach($config as $setting => $value)

                        <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label">{{ $value->label }}</label>
                            </div>
                            <div class="field-body">
                                <div class="field">

                                    <p class="control">
                                        @if($value->inputType === \App\Services\SettingEntity::TYPE_BOOL)
                                            <input type="checkbox" id="input_{{ $value->name }}"
                                                   name="{{ $value->name }}" value="true">
                                        @else
                                            <input class="input is-static has-background-white"
                                                   id="input_{{ $value->name }}" name="{{ $value->name }}"
                                                   value="{{ $value->value }}" required>
                                    @endif
                                    @if($value->commentLines)
                                        <p>{{ implode('<br>', $value->commentLines) }}</p>
                                        @endif
                                        </p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </article>
        @endforeach


        <button type="submit" class="button is-success is-outlined">Save and apply changes</button>
    </form>
@endsection


@section('js')
    <script>
        console.log('got here!');
    </script>
@endsection
