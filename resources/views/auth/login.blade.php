@extends('layouts.navbarLogin')

@section
<div class="offset-lg-3 col-lg-6 mt-5">
    {{-- mensajes de error --}}
    @include('partials.mensajes')
    <div class='card'>
        <div class ="card-header bg-secondary text-light">
            Login
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
                <a href="{{url('/')}}" class="btn btn-secondary"> Cancelar</a>
              </form>

              <a href="{{url('/newUser')}}">No tens cap compte? Crea't un</a>
        </div>

    </div>
</div>
@endsection