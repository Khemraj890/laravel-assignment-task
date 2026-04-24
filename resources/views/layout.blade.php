<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Tadieu') }}</title>

        {{-- Same as design.html: Tailwind 4 in the browser, then Basecoat. Do not add compiled Tailwind 3 (preflight) or it will override Basecoat inputs/buttons. --}}
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/basecoat-css@0.3.2/dist/basecoat.cdn.min.css">

        <style>
            @keyframes newTaskHighlight {
                0% { background-color: #ecfdf5; }
                100% { background-color: transparent; }
            }
            .new-task-highlight {
                animation: newTaskHighlight 2.2s ease-out forwards;
                border-radius: 0.375rem;
            }
        </style>

        @vite(['resources/js/app.js'])
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        @yield('content')
    </body>
</html>
