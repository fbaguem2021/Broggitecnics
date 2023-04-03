<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.13.0/mapbox-gl.css' rel='stylesheet' />
    @yield('head')
    @vite(['resources/css/app.css', 'resources/css/app.scss', 'resources/js/app.js'])
    <title>Broggi112</title>
    <link
  href="https://api.tiles.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.css"
  rel="stylesheet"
/>
</head>

<body>
    <div id="wrapper">
        <div id="header">
            <div class="header-inner" style="height: 50px">
                <a href="" id="logo" alt="Brogi112 - home">
                    <svg x="0px" y="0px" viewBox="0 0 50 50" fill="none">
                        <g clip-path="url(#clip0_177_786)">
                            <path
                                d="M27.2727 50H22.7273C22.5162 49.9238 22.2995 49.8643 22.0791 49.8221C17.0482 49.2373 12.3149 47.1338 8.5093 43.7918C4.70372 40.4498 2.00647 36.0278 0.77668 31.1146C0.464427 29.8419 0.256917 28.5534 0 27.2727V22.7273C0.0765141 22.5165 0.135347 22.2996 0.175889 22.0791C0.761025 17.0477 2.86508 12.314 6.20785 8.50835C9.55063 4.70272 13.9735 2.00577 18.8874 0.77668C20.1581 0.464427 21.4466 0.256917 22.7273 0H50V27.2727C49.9238 27.4838 49.8643 27.7005 49.8221 27.9209C48.6601 38.1423 41.1759 46.7134 31.2095 49.2095C29.9111 49.5277 28.585 49.7372 27.2727 50ZM46.081 3.96443C45.7095 3.94466 45.4545 3.92095 45.1976 3.92095C38.4137 3.92095 31.6285 3.92095 24.8419 3.92095C24.1502 3.90831 23.4585 3.93933 22.7708 4.01383C10.585 5.54941 2.53162 15.9862 4.1502 28.1443C5.40514 37.5949 13.7253 45.3814 23.2628 46.0277C35.8379 46.8794 46.0277 37.419 46.083 24.8281C46.1008 21.0395 46.083 17.253 46.083 13.4644L46.081 3.96443Z"
                                fill="white" />
                            <path
                                d="M23.9268 42.5317C21.0315 42.5317 19.0236 41.7313 17.3794 40.1166C14.8398 37.6265 12.2983 35.1384 9.84575 32.5653C7.54338 30.1522 6.9169 27.2728 7.89318 24.0672C8.2667 22.8419 8.72125 21.6404 9.18172 20.4447C9.38364 19.8933 9.71799 19.4 10.1553 19.0081C10.5927 18.6163 11.1197 18.338 11.6898 18.1976C12.26 18.0572 12.856 18.0592 13.4253 18.2032C13.9945 18.3473 14.5197 18.6291 14.9544 19.0238C15.8378 19.8143 16.6679 20.6621 17.4703 21.5336C18.4446 22.5909 18.656 23.8439 18.2272 25.2095C18.14 25.5268 18.0215 25.8347 17.8734 26.1285C17.4149 26.9526 17.6244 27.5336 18.3201 28.1641C19.5869 29.3123 20.7884 30.5356 21.9465 31.7985C22.5058 32.4052 23.0118 32.5158 23.7489 32.1937C24.3717 31.8915 25.0389 31.6913 25.7252 31.6008C28.1323 31.3894 31.9663 35.1186 31.8952 37.5297C31.8576 38.7688 31.3794 39.9012 30.2845 40.4131C28.0888 41.4684 25.8398 42.4269 23.9268 42.5317ZM27.6126 37.4645L27.6916 37.1324C27.3458 36.8321 26.9446 36.5751 26.6639 36.2234C26.1778 35.6087 25.6758 35.4882 24.9742 35.8558C24.3655 36.172 23.6837 36.3459 23.0394 36.5949C22.4604 36.6838 21.7272 36.3321 21.0849 35.6917C18.7963 33.417 16.7489 31.1008 14.4703 28.8162C13.8023 28.1463 13.6481 28.0949 13.4149 26.921C13.2548 26.1147 14.0335 25.3933 14.2568 24.6048C14.3155 24.2831 14.2444 23.9513 14.0592 23.6819C13.7094 23.172 13.2687 22.7313 12.6956 22.083C12.2212 23.5 11.7371 24.6226 11.4801 25.7965C11.3006 26.5389 11.3205 27.3156 11.5377 28.0479C11.755 28.7802 12.1619 29.442 12.7173 29.9664C15.16 32.4131 17.6046 34.8564 20.0513 37.2965C21.0394 38.2846 22.2726 38.8044 23.6422 38.5593C25.0118 38.3143 26.2944 37.84 27.6126 37.4645Z"
                                fill="white" />
                            <path
                                d="M33.0958 15.5259C33.0958 13.4469 33.0958 11.3678 33.0958 9.28876C33.0745 8.91988 33.0964 8.54978 33.161 8.18599C33.3586 7.35595 33.8151 6.76109 34.7835 6.79271C35.6669 6.82038 36.2993 7.41128 36.3408 8.4014C36.3962 9.69192 36.3586 10.9864 36.3606 12.2808C36.3606 12.6346 36.325 12.8895 36.8764 12.6761C40.8823 11.0951 43.7262 12.5891 44.5483 16.7255C44.9713 18.848 44.6353 20.8639 43.2558 22.6227C41.8309 24.437 38.9436 25.2868 36.9713 23.7097C36.6867 23.4824 36.4396 23.1761 36.0938 23.684C35.6175 24.3876 34.908 24.5635 34.1472 24.2552C33.2914 23.9133 33.0997 23.1504 33.0977 22.3204C33.0938 20.0536 33.0977 17.7868 33.0958 15.5259ZM41.4159 18.2354C41.4159 16.2888 40.3547 14.8757 38.8883 14.8757C37.4219 14.8757 36.3191 16.3441 36.3665 18.2473C36.406 20.2235 37.491 21.5654 38.989 21.5141C40.4555 21.4627 41.41 20.1741 41.4159 18.2354Z"
                                fill="#18AEC5" />
                            <path
                                d="M27.5784 18.2692C27.5784 19.6526 27.6021 21.036 27.5784 22.4194C27.5468 23.7494 26.9855 24.3759 25.9341 24.3443C24.946 24.3146 24.4183 23.7059 24.4084 22.4431C24.3847 19.6275 24.3847 16.81 24.4084 13.9905C24.4203 12.6921 25.0309 12.0142 26.0507 12.0518C27.023 12.0854 27.5507 12.7059 27.5784 13.9668C27.6041 15.4036 27.5784 16.8364 27.5784 18.2692Z"
                                fill="#FCC519" />
                            <path
                                d="M30.5304 16.4035C31.5047 16.3877 32.4118 17.2612 32.4098 18.2158C32.3982 18.7003 32.203 19.1624 31.8636 19.5084C31.5242 19.8544 31.066 20.0585 30.5818 20.0794C30.0974 20.0658 29.6355 19.8722 29.2862 19.5364C28.9368 19.2006 28.7252 18.7467 28.6924 18.2632C28.6569 17.3324 29.5561 16.4253 30.5304 16.4035Z"
                                fill="#E01C7E" />
                            <path
                                d="M27.7915 9.00022C27.7718 9.98836 26.8548 10.8856 25.92 10.8322C24.9852 10.7789 24.1216 9.79864 24.171 8.85595C24.2164 7.96465 25.1196 7.10299 25.9971 7.11089C26.4844 7.12691 26.9459 7.33409 27.2817 7.68764C27.6175 8.04119 27.8006 8.5127 27.7915 9.00022Z"
                                fill="#FCC519" />
                        </g>
                        <defs>
                            <clipPath id="clip0_177_786">
                                <rect width="50" height="50" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                </a>
                <button id="login">Login</button>
                <div id="line"></div>
            </div>
        </div>

        <div id="content">
            @yield('content')
        </div>
    </div>
</body>

</html>