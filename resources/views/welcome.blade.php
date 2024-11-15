<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Styles / Scripts -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <h1>Hello!</h1>

        @livewireScripts
    </body>
</html>
