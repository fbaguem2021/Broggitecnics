@extends('layout.navbarLogin')

@section('contenido')
    {{$user->rol->nombre}}
@endsection