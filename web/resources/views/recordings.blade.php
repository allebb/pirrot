<h1>File recordings</h1>

@if($recordings->count() > 0)
    <table>
        <tr>
            <th>List of recordings</th>
        </tr>
        @foreach($recordings as $recording)
            <tr>
                <td>{{ rtrim($recording->getFilename(), '.'.$recording->getExtension()) }}.{{ $recording->getExtension() }} ({{ ($recording->getSize() / 1024) }}KB)
                    Created at: {{ date('H:i:s jS M Y', $recording->getMTime()) }} [<a href="{{ route('download-recording', ['filename' => rtrim($recording->getFilename(), '.'.$recording->getExtension())]) }}">Download</a>] [<a href="{{ route('delete-recording', ['filename' => rtrim($recording->getFilename(), '.'.$recording->getExtension())]) }}">Delete</a>]</td>
            </tr>
        @endforeach

    </table>
@else
    <p>No recordings found.</p>
@endif
