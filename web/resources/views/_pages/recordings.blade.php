@extends('_layouts.master')


@section('html-title', 'Recordings - Pirrot Web Interface')
@section('title', 'Recordings')

@section('content')
    <h2 class="subtitle">Manage stored transmission recordings</h2>

    @if(!app('pirrot.config')->store_recordings)

        <article class="message is-danger">
            <div class="message-body">
                Your settings are currently set to not record transmissions, if you wish to listen back or download
                recordings, you must first enable the <strong>store_recordings</strong>
                option in your configuration as at present it is disabled.
            </div>
        </article>
    @endif

    <p style="padding-bottom: 1.6rem;">You can configure your recording settings from the <a
            href="{{ route('settings') }}">Settings</a> page.</p>

    @if($recordings->count() > 0)
        <article class="message pt-5">
            <div class="message-header">
                <p>Stored transmissions</p>
            </div>
            <table id="recordings-table" class="table is-fullwidth">
                @foreach($recordings as $recording)
                    <tr>
                        <td>{{ rtrim($recording->getFilename(), '.'.$recording->getExtension()) }}
                            .{{ $recording->getExtension() }} ({{ ($recording->getSize() / 1024) }}KB)
                            Created at: {{ date('H:i:s jS M Y', $recording->getMTime()) }}

                            <button class="button is-small is-outlined btn-play-audio"
                                    data-filename="{{ rtrim($recording->getFilename(), '.'.$recording->getExtension()) }}">
                                Playback
                            </button>
                            <button class="button is-small is-outlined btn-download-audio"
                                    data-filename="{{ rtrim($recording->getFilename(), '.'.$recording->getExtension()) }}">
                                Download
                            </button>
                            <button class="button is-small is-outlined is-danger btn-delete-audio"
                                    data-filename="{{ rtrim($recording->getFilename(), '.'.$recording->getExtension()) }}">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </article>
    @else
        <article class="message pt-5">
            <div class="message-header">
                <p>Stored transmissions</p>
            </div>
            <div class="message-body">
                <p style="padding: 4rem; text-align: center;">No recordings found.</p>
            </div>
        </article>
    @endif

    @if(app('pirrot.config')->purge_recording_after > 0)
        <p class="has-text-danger">* Based on your current configuration, these recordings are automatically purged
            after {{ number_format(app('pirrot.config')->purge_recording_after) }} days. </p>
    @endif

    <div id="player-modal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-content">
            <article class="message">
                <div class="message-header">
                    <p>Audio Player</p>
                    <button id="player-close" class="delete" aria-label="delete"></button>
                </div>
                <div class="message-body">
                    <audio id="audio-player" controls autoplay>
                        <source id="player-source" type="audio/ogg">
                        Oh no! Your browser does not support the HTML5 audio player, we'd recommend you download the
                        recording
                        files instead!
                    </audio>
                </div>
            </article>

        </div>
        <button class="modal-close is-large" aria-label="close"></button>
    </div>

@endsection

@section('css')
    <style>
        audio {
            width: 100%;
            border-radius: 0px;
        }
    </style>
@endsection

@section('js')
    <script>

        function closeAudioPlayerModal() {
            $("#player-modal").removeClass('is-active');
            $("#player-modal").removeClass('is-clipped');
        }

        function loadAudioFile(sourceUrl) {
            var audio = $("#audio-player");
            $("#player-source").attr("src", sourceUrl);
            audio[0].pause();
            audio[0].load();//suspends and restores all audio element
            audio[0].oncanplaythrough = audio[0].play();
        }

        $('.btn-play-audio').on('click', function (e) {
            var file = $(this).data('filename');
            loadAudioFile('/recordings/' + file + '.ogg');
            $("#player-modal").addClass('is-active');
            $("#player-modal").addClass('is-clipped');
        });

        $('.btn-download-audio').on('click', function (e) {
            var file = $(this).data('filename');
            window.location = '/audio-recordings/' + file + '/download';
        });

        $('.btn-delete-audio').on('click', function (e) {
            var file = $(this).data('filename');
            var fileRow = $(this).closest('tr');

            if (confirm('Are you sure you want to delete this recording?') === false) {
                return;
            }

            fetch('/audio-recordings/' + file + '/delete', {
                method: 'get',
            }).then(result => {
                $(fileRow).remove();
                if (document.getElementById("recordings-table").rows.length === 0) {
                    window.location.reload();
                }
            }).catch(error => {
                alert('An error occurred and the recording could not be deleted, please refresh and try again!');
            });

        });

        $('#player-close').on('click', function () {
            closeAudioPlayerModal();
        });

        $('.modal-close').on('click', function () {
            closeAudioPlayerModal();
        });

        $('.modal-background').on('click', function () {
            closeAudioPlayerModal();
        });


    </script>
@endsection
