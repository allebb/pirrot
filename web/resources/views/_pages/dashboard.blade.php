@extends('_layouts.master')

@section('html-title', 'Dashboard - Pirrot Web Interface')
@section('title', 'Dashboard')

@section('content')
    <h2 class="subtitle">System statistics</h2>

    <article class="message">
        <div class="message-header">
            <p>Normal message</p>
            <button class="delete" aria-label="delete"></button>
        </div>
        <div class="message-body">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. <strong>Pellentesque risus mi</strong>, tempus quis
            placerat ut, porta nec nulla.Nullam gravida purus diam, et dictum <a>felis venenatis</a> efficitur. Aenean
            ac <em>eleifend lacus</em>, in mollis lectus.
        </div>
    </article>
@endsection

@section('js')
    <script>
        console.log('got here!');
    </script>
@endsection
