@extends('layout.base')
@section('page-title', "Gestió d'expedients")

@section('content')
  <expedients-app style="display:block; height:100%;"></expedients-app>
@endsection

@section('scripts')
  @vite('node_modules/bootstrap/dist/js/bootstrap.js')
@endsection