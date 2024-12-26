<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel')</title>
<script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
</head>

<body>
    @include('frontendlayout.header')
    <main>
   @yield('content')
    </main>
    @yield('scripts')
    @include('frontendlayout.footer')
</body>
</html>