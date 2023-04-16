<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite(['resources/scss/app.scss', 'resources/css/app.css', 'resources/js/app.js'])
  <style>
      carta-app {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        display: flex;
        flex-direction: column;
      }
  </style>
  <title>Carta</title>
</head>
<body>
  {{-- <h1>{{$message}}</h1> --}}
  <carta-app></carta-app>
</body>
</html>