<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Anilibrary - service for scraping anime">
    <meta name="keywords" content="anilibrary, anime, scraping">

    <title inertia>{{ config('app.name', 'anilibrary') }}</title>

    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="font-sans antialiased">
@inertia
</body>
</html>
