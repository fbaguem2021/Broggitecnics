@extends('layout.base')

@section('style')
    @vite('resources/css/login.css')
@endsection

@section('content')
    {{-- mensajes de error --}}
    {{-- @include('partials.mensajes') --}}
    <div class='card' id="register">
        <a id="close-btn" href="{{url('/')}}">
            <i class="bi bi-x-lg"></i>
        </a>
        <div class="card-body text-center">
            <h3 class="card-title">NOU COMPTE</h3> 
            <p class="card-text mt-4">El teu nom d'usuari serà la primera lletra del teu nom + el teu primer cognom.</p>
            <div class="form-container mt-4">
                <form action="{{action([App\Http\Controllers\UsuariController::class, 'store'])}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        {{-- <label for="nom" class="form-label">Nom</label> --}}
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom">
                    </div>
                    <div class="mb-3">
                        {{-- <label for="cognoms" class="form-label">Cognoms</label> --}}
                        <input type="text" class="form-control" id="cognoms" name="cognoms" placeholder="Cognoms">
                    </div>
                    <div class="mb-3">
                        {{-- <label for="pass" class="form-label">Contrasenya</label> --}}
                        <input type="password" class="form-control" id="pass" name="pass" placeholder="Contrasenya">
                    </div>
                    
                    <button type="submit" id="submit-btn" class="btn btn-outline-primary mt-4">Acceptar</button>
                </form>
            </div> 
        </div>
        <div style="position: absolute;
        top: 14%;
        right: -80%;
        width: 300px;">
            <p class="text-center">-- DEV --</p>
            <p class="m-0">← esto se puede borrar es pa poner algo que sino queda muy seco</p>
            <br>
            <p>Falta visiblitat de quin sera el nom d'usuari</p>
        </div>
    </div>

  @endsection