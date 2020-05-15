@extends('_layouts.master')


@section('html-title', 'Settings - Pirrot Web Interface')
@section('title', 'Settings')

@section('content')
    <h2 class="subtitle">Review and update your repeater settings</h2>

    <form id="form-settings" method="POST" action="{{ route('settings') }}">


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
                                                   name="{{ $value->name }}" value="true"
                                                   @if($value->value == 'true') checked @endif>
                                        @else
                                            <input class="input is-static has-background-white"
                                                   id="input_{{ $value->name }}" name="{{ $value->name }}"
                                                   value="{{ $value->value }}" required>
                                    @endif
                                    @if($value->commentLines)
                                        <p>{!! implode('<br>', $value->commentLines) !!}</p>
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
        $('#form-settings').on('submit', function (e) {
            e.preventDefault();
            var form = $(this).serializeArray();
            fetch('/settings', {
                method: 'post',
                body: JSON.stringify(form),
            }).then(result => {
                alert('Settings updated, now reloading Pirrot!');
            }).catch(error => {
                alert('An error occurred and your changes could not be saved, please refresh and try again!');
            });
        })
    </script>
@endsection
