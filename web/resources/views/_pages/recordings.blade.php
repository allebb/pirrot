@extends('_layouts.master')


@section('html-title', 'Recordings - Pirrot Web Interface')
@section('title', 'Recordings')

@section('content')
    <h2 class="subtitle">Manage your stored audio recordings</h2>

    @if(!app('pirrot.config')->store_recordings)

        <article class="message is-danger">
            <div class="message-body">
                Your settings are currently set to not record transmissions, ff you wish to listen back or download recordings, you must first enable the <strong>store_recordings</strong>
                option in your configuration as at present it is disabled.
            </div>
        </article>
    @endif

    @if($recordings->count() > 0)
        <table>
            <tr>
                <th>List of recordings</th>
            </tr>
            @foreach($recordings as $recording)
                <tr>
                    <td>{{ rtrim($recording->getFilename(), '.'.$recording->getExtension()) }}
                        .{{ $recording->getExtension() }} ({{ ($recording->getSize() / 1024) }}KB)
                        Created at: {{ date('H:i:s jS M Y', $recording->getMTime()) }} [<a
                            href="{{ route('download-recording', ['filename' => rtrim($recording->getFilename(), '.'.$recording->getExtension())]) }}">Download</a>]
                        [<a href="{{ route('delete-recording', ['filename' => rtrim($recording->getFilename(), '.'.$recording->getExtension())]) }}">Delete</a>]
                        <audio controls>
                            <source src="/recordings/{{ $recording->getFilename() }}" type="audio/ogg">
                            Your browser does not support the HTML5 audio player.
                        </audio>
                    </td>
                </tr>
            @endforeach

        </table>
    @else
        <p style="padding: 4rem; text-align: center;">No recordings found.</p>
    @endif

    @if(app('pirrot.config')->purge_recording_after > 0)
        <div class="notification">
            <p>Based on your current configuration, these recordings are automatically purged
                after {{ number_format(app('pirrot.config')->purge_recording_after) }} days. </p>
        </div>
    @endif
@endsection


@section('js')
    <script>
        console.log('got here!');
    </script>
@endsection
