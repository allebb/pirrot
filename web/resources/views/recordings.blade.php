<h1>File recordings</h1>

@if($recordings->count() > 0)
    <table>
        <tr>
            <th>List of recordings</th>
        </tr>
        @foreach($recordings as $recording)
            <tr>
                <td>{{ $recording->getFilename() }} - {{ $recording->extension() }} [{{ $recording->getExtension() }}] {{ ($recording->getSize() * 1024) }}KB Type: {{ $recording->getType() }}</td>
            </tr>
        @endforeach

    </table>
@else
    <p>No recordings found.</p>
@endif
