<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CalcTek</title>
    </head>
    <body class="bg-zinc-900 min-h-screen font-inter">
        <div id="app" />

        @vite('resources/js/app.ts')
    </body>
</html>
