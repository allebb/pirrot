<h1>File recordings</h1>

@if($recordings->count() > 0)
    <table>
        <tr>
            <th>List of recordings</th>
        </tr>
        @foreach($recordings as $recording)
            <tr>
                <td>{{ rtrim($recording->getFilename(), '.'.$recording->getExtension()) }}
                    - {{ $recording->getExtension() }} Size: {{ ($recording->getSize() * 1024) }}KB
                    Type: {{ $recording->getType() }} Created at: {{ $recording->getCTime() }} [<a href="{{ route('download-recording', ['filename' => rtrim($recording->getFilename(), '.'.$recording->getExtension())]) }}">Download</a>]</td>
            </tr>
        @endforeach

    </table>
@else
    <p>No recordings found.</p>
@endif
