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
                                        @if($value->inputType === \App\Services\DTO\Setting::TYPE_BOOL)
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


    <div id="reloading-modal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-content">
            <article class="message">
                <div class="message-header">
                    <p>Settings</p>
                </div>
                <div class="message-body">
                    <div class="pb-5">
                        <div class="sk-chase pt-3">
                            <div class="sk-chase-dot"></div>
                            <div class="sk-chase-dot"></div>
                            <div class="sk-chase-dot"></div>
                            <div class="sk-chase-dot"></div>
                            <div class="sk-chase-dot"></div>
                            <div class="sk-chase-dot"></div>
                        </div>
                    </div>
                    <p class="pb-3 has-text-centered">Please wait whilst your changes are being applied...</p>

                    <p class="is-size-7" style="padding-top: 2rem;">If something went wrong with your new settings, a
                        copy of your old <code>pirrot.conf</code>
                        file
                        has been backed up under <code>/opt/pirrot/storage/backups</code> which you can revert back to
                        if
                        required.</p>
                </div>
            </article>
        </div>
    </div>
@endsection


@section('js')
    <script>
        $('#form-settings').on('submit', function (e) {
            e.preventDefault();

            if (confirm('Are you sure you want to apply these changes now? The repeater service will be restarted and will be unavailable for a few seconds!') !== true) {
                return;
            }

            showReloadingScreen();

            var form = $(this).serializeArray();
            fetch('/settings', {
                method: 'post',
                body: JSON.stringify(form),
            })
                .then(response => response.json())
                .then(result => {
                    setTimeout(function () {
                        window.location = result.after_url;
                    }, 5000);
                })
                .catch(error => {
                    alert('An error occurred and your changes could not be saved, please refresh and try again!');
                });
        });

        function showReloadingScreen() {
            $("#reloading-modal").addClass('is-active');
        }

    </script>
@endsection
