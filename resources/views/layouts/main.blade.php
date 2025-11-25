<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Stadium')</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Open+Sans:wght@500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>

<body class="font-body min-h-screen">
    <main class="flex-grow">
        @yield('hero')
        @yield('features')
        @yield('content')
    </main>

    <footer>
        @include('components.footer')
    </footer>
    @vite('resources/js/app.js')
    @yield('scripts')
</body>
</html>
