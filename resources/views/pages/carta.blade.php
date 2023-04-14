<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite(['resources/scss/app.scss', 'resources/css/app.css', 'resources/js/app.js'])
  <title>Carta</title>
</head>
<body>
  <h1>{{$message}}</h1>
  <carta-app></carta-app>
</body>
</html>