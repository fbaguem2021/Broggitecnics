<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('titulo')</title>
    <link rel="icon" type="image/x-icon" href="/Laravel/ABP_JoseNirella/public/img/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="{{asset('plantilla.css')}}"> --}}
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="{{URL('/')}}"><img src="/Laravel/ABP_JoseNirella/public/img/logo.jfif" alt="" width="100px"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <form class="d-flex" role="search">
            <ul class="navbar-nav">
              @if (Auth::check())
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  {{Auth::user()->nom}} {{ Auth::user()->cognoms}}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="{{url('/logout')}}"><i class="fa fa-sign-out" aria-hidden="true">
                    </i> Tancar sessió</a>
                </div>
              </li>
              @else
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/login')}}"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</a>
                </li>
              @endif
            </ul>
          </form>
          </div>
        </div>
      </nav>

      {{-- Container --}}
      <div class="container-fluid">
        @yield('contenido')
      </div>
      <script src="https://kit.fontawesome.com/eb6de7a2c5.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>