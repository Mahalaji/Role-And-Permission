<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

</head>

<body>
    <h1>Inspiring change, one insight at a time.</h1>

    @if (Route::has('login'))
    <div class="auth-buttons">
        @auth
        <a href="{{ url('/dashboard') }}">Dashboard</a>
        @else
        <a href="{{ route('login') }}">Log in</a>

        @if (Route::has('register'))
        <a href="{{ route('register') }}">Register</a>
        @endif
        @endauth
    </div>
    @endif
</body>

</html>
