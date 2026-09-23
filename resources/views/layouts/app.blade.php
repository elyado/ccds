<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Centro Cultural Domingo Soler')</title>
    <meta name="description" content="@yield('meta_description', 'Centro Cultural Domingo Soler en Acapulco.')">
    <meta property="og:title" content="@yield('title', 'Centro Cultural Domingo Soler')">
    <meta property="og:description" content="@yield('meta_description', 'Centro Cultural Domingo Soler en Acapulco.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Centro Cultural Domingo Soler')">
    <meta name="twitter:description" content="@yield('meta_description', 'Centro Cultural Domingo Soler en Acapulco.')">
    @hasSection('meta_image')
        <meta property="og:image" content="@yield('meta_image')">
        <meta name="twitter:image" content="@yield('meta_image')">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('partials.header')

    @yield('content')

    @include('partials.footer')
</body>
</html>
