<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ isset($title) ? $title .' | CMS '. config('app.name') : config('app.name') }}</title>
  <link rel="icon" type="image/x-icon" href="{{asset('/assets/image/favicon.ico')}}">

  @vite(['resources/js/entry/cms/app.js', 'resources/css/cms.css'])
  @stack('javascriptNduwur')
</head>

<body 
  class="scroll-smooth"
  x-init="darkMode = JSON.parse(localStorage.getItem('darkMode')); $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
  x-data="{ page: 'dashboard', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
  :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
>
  <div class="flex h-screen overflow-hidden scroll-smooth">
    <x-layouts.cms.partials.sidebar />
    <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
      <x-layouts.cms.partials.header />
      <main class="p-4">
        {{ $slot }}
      </main>
    </div>
  </div>
  @stack('javascriptNgisor')
</body>
</html>