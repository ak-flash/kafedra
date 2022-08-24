<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">

        <meta name="application-name" content="{{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ isset($title) ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @livewireScripts
        @stack('head_scripts')
    </head>

    <body class="antialiased">

        {{ $slot }}

        @stack('scripts')
        @livewire('notifications')

        <script>
            window.addEventListener('need-reload', event => {
                location.reload();
            });
        </script>
    </body>
</html>
