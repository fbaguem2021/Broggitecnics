@extends('layout.base')

@section('style')
    @vite('resources/css/landing.css')
@endsection

@section('content')
    <div class="container menu" id="menu-admin">
            <div class="container block-lt">
                <div class="row h-100 p-4">
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body px-4">
                                <h3 class="card-title fw-bold text-center">Adminisitrar expedients</h3>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="container block-lb">
                <div class="row h-100 p-4">
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h3 class="card-title fw-bold text-center">Administrar base de dades</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container block-rt">
                <div class="title">
                    <h3 class="fw-bold text-center">Analitiques</h3>
                </div>
            </div>
            <div class="container block-rb">
                <div class="title">
                    <h3 class="fw-bold text-center">Operadors</h3>
                </div>
            </div>
        <div class="menu-bg"></div>
    </div>
    <div id="stripes-1">
        <svg width="200" height="80" viewBox="0 0 200 80" fill="none">
            <path d="M0 0H200V20H0V0Z" fill="#E2AA12"/>
            <path d="M0 30H200V50H0V30Z" fill="#E1127A"/>
            <path d="M0 60H200V80H0V60Z" fill="#12C7E2"/>
        </svg>            
    </div>
    <div id="stripes-2">
        <svg width="200" height="80" viewBox="0 0 200 80" fill="none">
            <path d="M0 0H200V20H0V0Z" fill="#E2AA12"/>
            <path d="M0 30H200V50H0V30Z" fill="#E1127A"/>
            <path d="M0 60H200V80H0V60Z" fill="#12C7E2"/>
        </svg>            
    </div>
    </div>
@endsection