<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ isset($title) ? $title .' | '. config('app.name') : config('app.name') }}</title>
        <link rel="icon" type="image/x-icon" href="{{asset('/assets/image/favicon.ico')}}">

        @vite(['resources/js/entry/frontend/app.js', 'resources/css/app.css'])
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
        <main class="mx-auto text-gray-900 dark:text-white dark:bg-boxdark-2">
            {{ $slot }}
        </main>
        @stack('javascriptNgisor')
    </body>
</html>
