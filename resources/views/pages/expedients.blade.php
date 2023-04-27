@extends('layout.base')
@section('tab-title', "Expedients")
@section('page-title', "Gestió d'expedients")
@section('links')
    @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
@endsection

@section('content')
  <expedients-app style="display:block; height:100%;"></expedients-app>
@endsection