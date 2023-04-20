@extends('layout.base')

@section('style')
    @vite('resources/css/landing.css')
@endsection

@section('content')

@if (Session::has("error"))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ Session::get("error") }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

  <div class="login-container">  
      <div class='card' id="login">
        <a id="close-btn" href="{{url('/')}}">
          <i class="bi bi-x-lg"></i>
        </a>
        <div class="card-body text-center">
          <h3 class="card-title">LOGIN</h3>  
          <p class="card-text my-4">Introdueix el teu usuari i contrasenya </p>
          <div class="form-container">
            <form action="{{action([App\Http\Controllers\UsuariController::class, 'login'])}}" method="POST">
                @csrf
                <div class="mb-3">
                  {{-- <label for="userName" class="form-label">Nom d'usuari</label> --}}
                  <input type="text" class="form-control" id="userName" name="userName" placeholder="Usuari" autofocus value="{{old('userName')}}">
                </div>
                <div class="mb-3">
                  {{-- <label for="password1" class="form-label">Contrasenya</label> --}}
                  <input type="password" class="form-control" id="password1" name="password1" placeholder="Contrasenya">
                </div>
                <button type="submit" id="submit-btn" class="btn btn-outline-primary mt-4">Aceptar</button>
              </form>

              <div class="mt-4">
                <span>No tens cap compte? </span><a href="{{url('/registre')}}" id="link-newAccount">Crea't un</a>
              </div>
            </div>

              
        </div>

      </div>
  </div>
@endsection