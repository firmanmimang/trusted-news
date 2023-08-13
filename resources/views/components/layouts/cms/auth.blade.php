<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In | CMS</title>
  <script defer src="{{asset('cms/bundle.js')}}" ></script>
  @vite(['resources/js/entry/cms/app.js', 'resources/css/cms.css'])
</head>

<body 
  x-init="darkMode = JSON.parse(localStorage.getItem('darkMode')); $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
  x-data="{ page: 'signin', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
  :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
>
  <main class="">
    {{ $slot }}
  </main>
</body>
</html>