@extends('layout.base')
@section('title', 'Operadors')
@section('links')
    @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
@endsection
@section('content')
    {{-- <div id="operadors"></div> --}}
    <operadors></operadors>
@endsection