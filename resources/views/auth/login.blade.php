@extends('layout.navbarLogin')
@section('titulo','login')
@section('contenido')

<div class="offset-sm-4 offset-lg-5 col-sm-6 col-md-12 col-lg-6 mt-5">
    {{-- mensajes de error --}}
    {{-- @include('partials.mensajes') --}}
    <div class='card col-sm-4'>
        <div class ="card-header bg-secondary text-light text-center">
            Inicia sessió
        </div>
        <div class="card-body">
            <form action="{{action([App\Http\Controllers\UsuarioController::class, 'login'])}}" method="POST">
                @csrf
                <div class="mb-3">
                  <label for="userName" class="form-label">Nom d'usuari</label>
                  <input type="text" class="form-control" id="userName" name="userName" autofocus value="{{old('userName')}}">
                </div>
                <div class="mb-3">
                  <label for="password1" class="form-label">Contrasenya</label>
                  <input type="password" class="form-control" id="password1" name="password1">
                </div>
                <div class="d-flex justify-content-between">
                  <a href="{{url('/')}}" class="btn btn-secondary"> Cancelar</a>
                  <button type="submit" class="btn btn-primary ms-1"> Aceptar</button> 
                </div>
               
              </form>
              <div class="text-center mt-3">
                <a href="{{url('/registre')}}">No tens cap compte? Crea't un</a>
              </div>
              
        </div>

    </div>
</div>
@endsection