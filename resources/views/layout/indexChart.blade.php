@extends('layout.base')
@section('page-title', "Gràfics")
@section('style')
    @vite('resources/css/landing.css')    
@endsection

@section('content')
<div class="container-fluid " style="height: 100%"> 
    <div class="row" style="height: 100%">
        {{-- MENU --}}
        <div class="pt-4 text-center col-md-2 bg-primary text-white" > 
            <h1><i class="bi bi-clipboard-data pe-2"></i>GRAFICS</h1>
            <ul style="padding-left:0; list-style: none;" class="mt-5">
                <li ><a  style="text-decoration: none; color: white" href="{{url('chartLoc')}}">Top 10 municipis amb més incidents</a></li>
                <li class="mt-4"><a style="text-decoration: none; color:white" href="{{url('chartLlamada')}}">Top 10 municipis amb mes trucades</a></li>
            </ul>
        </div>

        {{-- CONTENIDO --}}
        <div class="col-md-10 " style="background-color: white; height: 100%">
            @yield('grafic')
        </div> 
    </div>
  </div>
  

    
@endsection

