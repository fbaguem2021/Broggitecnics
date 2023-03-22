@extends('layout.navbarLogin')
@section('titulo','registre')
@section('contenido')
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
      
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
  @endsection