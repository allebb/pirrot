<h1>File recordings</h1>

@if($recordings->count() > 0)
    <table>
        <tr>
            <th>List of recordings</th>
        </tr>
        @foreach($recordings as $recording)
            <tr>
                <td>{{ $recording }}</td>
            </tr>
        @endforeach

    </table>
@else
    <p>No recordings found.</p>
@endif
