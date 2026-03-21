<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SaborGestion - {{ $title ?? 'Sistema de Gestión' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        @include('layouts.sidebar')
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
