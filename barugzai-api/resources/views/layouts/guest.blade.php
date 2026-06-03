<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional Vite if still using it for JS -->
    @vite(['resources/js/app.js']) {{-- remove resources/css/app.css if it's Tailwind-based --}}
</head>
<body class="bg-light text-dark">

    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-5 bg-light">
        <div class="mb-4">
            <a href="/">
                {{-- Replace with <img src="..." alt="Logo"> if needed --}}
                <x-application-logo class="img-fluid" style="height: 80px;" />
            </a>
        </div>

        <div class="w-100" style="max-width: 500px;">
            <div class="card shadow-sm">
                <div class="card-body">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
