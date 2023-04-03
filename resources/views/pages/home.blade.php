@extends('layout.base')

@section('style')
    @vite(['resources/css/landing.css', 'resources/js/menu.js'])
@endsection

@section('content')
    @if (Auth::check())
        @switch(Auth::user()->rol->id)
            @case(1)
                @include('partials.menu.operador')
                @break
            @case(2)
                @include('partials.menu.supervisor')
                @break
            @case(3)
                @include('partials.menu.admin')
                @break
            @default
            <div class="container menu">
                <div class="menu-bg"></div>
            </div>
        @endswitch 
    @endif

    {{-- STRIPES SVG --}}
    <div id="stripes-1">
        <svg width="200" height="80" viewBox="0 0 200 80" fill="none">
            <path d="M0 0H200V20H0V0Z" fill="#E2AA12" />
            <path d="M0 30H200V50H0V30Z" fill="#E1127A" />
            <path d="M0 60H200V80H0V60Z" fill="#12C7E2" />
        </svg>
    </div>
    <div id="stripes-2">
        <svg width="200" height="80" viewBox="0 0 200 80" fill="none">
            <path d="M0 0H200V20H0V0Z" fill="#E2AA12" />
            <path d="M0 30H200V50H0V30Z" fill="#E1127A" />
            <path d="M0 60H200V80H0V60Z" fill="#12C7E2" />
        </svg>
    </div>
@endsection