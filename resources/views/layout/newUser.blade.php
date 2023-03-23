@extends('layout.navbarLogin')
@section('titulo','registre')
@section('contenido')
<div class="offset-sm-4 offset-lg-5 col-sm-6 col-md-12 col-lg-6 mt-5">
    {{-- mensajes de error --}}
    {{-- @include('partials.mensajes') --}}
    <div class='card col-sm-4'>
        <div class ="card-header bg-secondary text-light text-center">
            Crea un compte
        </div>
        <div class="card-body">
<form action="{{action([App\Http\Controllers\UsuarioController::class, 'store'])}}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" aria-describedby="nom">
    </div>
    <div class="mb-3">
        <label for="cognoms" class="form-label">Cognoms</label>
        <input type="text" class="form-control" id="cognoms" name="cognoms" aria-describedby="cognoms">
    </div>
    <div class="mb-3">
      <label for="pass" class="form-label">Contrasenya</label>
      <input type="password" class="form-control" id="pass" name="pass">
    </div>

    <input type="text" class="form-control" id="tipus" name="tipus" aria-describedby="email" value="1" hidden>
      
    <button type="submit" class="btn btn-primary">Acceptar</button>
  </form>
        </div>
    </div>
</div>
  @endsection