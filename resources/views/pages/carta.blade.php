<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
  <style>
    body {
      overflow-x: overlay;
    }
  </style>
  <title>Carta</title>
</head>
<body>
  <carta-app></carta-app>
   {{-- STRIPES SVG --}}
  <div id="stripes-1">
    <svg width="200" height="80" viewBox="0 0 200 80" fill="none">
        <path d="M0 0H200V20H0V0Z" fill="#E2AA12" />
        <path d="M0 30H200V50H0V30Z" fill="#E1127A" />
        <path d="M0 60H200V80H0V60Z" fill="#12C7E2" />
    </svg>
  </div>
  <div id="stripes-2">
      <svg width="200" height="80" viewBox="0 0 200 80" fill="none">
          <path d="M0 0H200V20H0V0Z" fill="#E2AA12" />
          <path d="M0 30H200V50H0V30Z" fill="#E1127A" />
          <path d="M0 60H200V80H0V60Z" fill="#12C7E2" />
      </svg>
  </div>
</body>
</html>