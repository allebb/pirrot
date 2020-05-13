<h1>File recordings</h1>

@if($recordings->count() > 0)
    <table>
        <tr>
            @foreach($recordings as $recording)

            @endforeach
        </tr>
    </table>
@else
    <p>No recordings found.</p>
@endif
