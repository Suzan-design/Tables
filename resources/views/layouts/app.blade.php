<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="admin, dashboard">
    <meta name="author" content="DexignZone">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Dompet : Payment Admin Template">
    <meta property="og:title" content="Dompet : Payment Admin Template">
    <meta property="og:description" content="Dompet : Payment Admin Template">
    <meta property="og:image" content="https://dompet.dexignlab.com/xhtml/social-image.png">
    <meta name="format-detection" content="telephone=no">

    <title>Restaurants Management</title>
    <!-- FAVICONS ICON -->
    <link href="{{ URL::asset('dashboard/images/favicon.png') }}" rel="shortcut icon" type="image/png">

    <link href="{{ URL::asset('dashboard/css/style.css') }}" rel="stylesheet">




    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
</head>

<body class="vh-100">
    @yield('login')


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ URL::asset('dashboard/vendor/global/global.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/js/custom.min.js') }}"></script>
    <script src="{{ URL::asset('dashboard/js/dlabnav-init.js') }}"></script>
    <script src="{{ URL::asset('dashboard/js/styleSwitcher.js') }}"></script>



</body>

</html>
