<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stadium Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#002F87] min-h-screen font-sans relative">
    <x-sidebar />
    <main class="ml-16 pt-6 dashboard-wrapper">
       
        @yield('content')
    </main>
    <footer class="ml-16 pt-6 dashboard-wrapper">
        @include('components.footer')
    </footer>
</body>
</html>
