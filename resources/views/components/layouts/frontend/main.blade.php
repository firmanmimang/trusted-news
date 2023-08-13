<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ isset($title) ? $title .' | '. config('app.name') : config('app.name') }}</title>
        <link rel="icon" type="image/x-icon" href="{{asset('/assets/image/favicon.ico')}}">

        {{-- font --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite(['resources/js/entry/frontend/app.js', 'resources/css/app.css'])
        @livewireStyles
        @stack('cssNduwur')
    </head>
    <body
        class="m-auto antialiased max-w-screen-2xl dark:text-white dark:bg-boxdark-2"
        x-data="{
            darkMode: (localStorage.getItem('darkMode') == 'true') || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
            toggleDarkMode() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('darkMode', this.darkMode);
                if (this.darkMode) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        }"
        x-init="darkMode = JSON.parse(localStorage.getItem('darkMode')) ?? window.matchMedia('(prefers-color-scheme: dark)').matches"
        :class="{'dark': darkMode == true}"
    >
        <header>
            <x-layouts.frontend.partials.navbar/>
        </header>
        <x-layouts.frontend.partials.navbar-bottom/>

        <main class="mx-auto text-gray-900 dark:text-white dark:bg-boxdark-2">
            {{ $slot }}
        </main>

        <footer class="text-gray-900 dark:text-white dark:bg-boxdark-2">
            <x-layouts.frontend.partials.footer />
        </footer>
        @livewireScripts
        @stack('javascriptNgisor')
    </body>
</html>
