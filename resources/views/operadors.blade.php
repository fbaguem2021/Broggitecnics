@extends('layout.base')
@section('title', 'Operadors')
@section('links')
    @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
@endsection
@section('content')
    {{-- <div id="operadors"></div> --}}
    @if(auth()->check())
    <script>
        window.Usuario = {!! auth()->user() !!}
        // console.log(window.Usuario);
    </script>
    @endif
    <operadors></operadors>
@endsection