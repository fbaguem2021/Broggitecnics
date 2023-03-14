@extends('layouts.navbarLogin')

@section('contenido')
    {{$user->rol->nombre}}
@endsection