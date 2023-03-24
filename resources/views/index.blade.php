@extends('layout.base')

@section('style')
    @vite('resources/css/landing.css')    
@endsection

@section('content')
    <div id="app-logo">
        <img src="{{ asset('assets/png/app-logo.png') }}" alt="broggi-logo">
    </div>
    <div id="footer">
        <p>Developed by</p>
        <ul id="devs">
            <li class="dev">
                <span>Guillem T.</span>
                <a href="https://github.com/gturro" target="_blank">
                    <img src="{{ asset('assets/png/github-logo.png')}}" alt="github - Guillem">
                </a>
            </li>
            <li class="dev">
                <span>Francesc B.</span>
                <a href="https://github.com/fbaguem2021" target="_blank">
                    <img src="{{ asset('assets/png/github-logo.png')}}" alt="github - Francesc">
                </a>
            </li>
            <li class="dev">
                <span>Jose N.</span>
                <a href="https://github.com/jnirella" target="_blank">
                    <img src="{{ asset('assets/png/github-logo.png')}}" alt="github - Jose">
                </a>
            </li>
        </ul>
    </div>
@endsection


