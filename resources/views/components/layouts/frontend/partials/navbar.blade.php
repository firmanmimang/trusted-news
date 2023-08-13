<div>
  <div class="w-full text-gray-700 bg-white dark:text-gray-200 dark:bg-gray-800">
    <div x-data="{ open: false }" class="flex flex-col px-4 mx-auto md:items-center md:justify-between md:flex-row md:px-5 lg:px-5">
      <div class="flex flex-row items-center justify-between p-3 pl-0">
          <a data-turbo-preload href="/" class="flex items-center gap-1 text-xl font-bold tracking-widest text-gray-900 uppercase rounded-lg dark:text-white focus:outline-none focus:shadow-outline">
              <img src="/assets/image/Logo_T_huruf.png" alt="Logo Trusted News" title="Trusted News" class="object-contain h-10">
          </a>
          <button class="rounded-lg md:hidden focus:outline-none focus:shadow-outline"
              @click="open = !open">
              <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                  <path x-show="!open" fill-rule="evenodd"
                      d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z"
                      clip-rule="evenodd"></path>
                  <path x-show="open" fill-rule="evenodd"
                      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                      clip-rule="evenodd"></path>
              </svg>
          </button>
      </div>
      <nav :class="{ 'flex': open, 'hidden': !open }" class="flex-col flex-grow hidden pb-4 md:pb-0 md:flex md:justify-between md:flex-row">
        <ul class="flex items-center gap-2 grow">
          <li>
            <a data-turbo-preload class="{{ request()->is('/') ? 'dark:!bg-gray-600 dark:!text-white !text-gray-900 !bg-gray-200' : '' }} px-4 py-2.5 mt-2 text-sm font-semibold bg-transparent rounded-lg dark:bg-transparent dark:hover:bg-gray-600 dark:focus:bg-gray-600 dark:focus:text-white dark:hover:text-white dark:text-gray-200 md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline transition duration-300"
              href="/">Beranda</a>
          </li>
          <li>
            <a data-turbo-preload class="{{ request()->is('about') ? 'dark:!bg-gray-600 dark:!text-white !text-gray-900 !bg-gray-200' : '' }} px-4 py-2.5 mt-2 text-sm font-semibold transition duration-300 bg-transparent rounded-lg dark:bg-transparent dark:hover:bg-gray-600 dark:focus:bg-gray-600 dark:focus:text-white dark:hover:text-white dark:text-gray-200 md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
              href="/about">Siapa Kami</a>
          </li>
          <li>
            <a data-turbo-preload class="{{ request()->is('contact') ? 'dark:!bg-gray-600 dark:!text-white !text-gray-900 !bg-gray-200' : '' }} px-4 py-2.5 mt-2 text-sm font-semibold transition duration-300 bg-transparent rounded-lg dark:bg-transparent dark:hover:bg-gray-600 dark:focus:bg-gray-600 dark:focus:text-white dark:hover:text-white dark:text-gray-200 md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
              href="/contact">Kontak</a>
          </li>
          <li>
            <a data-turbo-preload class="{{ request()->is('guest') ? 'dark:!bg-gray-600 dark:!text-white !text-gray-900 !bg-gray-200' : '' }} px-4 py-2.5 mt-2 text-sm font-semibold transition duration-300 bg-transparent rounded-lg dark:bg-transparent dark:hover:bg-gray-600 dark:focus:bg-gray-600 dark:focus:text-white dark:hover:text-white dark:text-gray-200 md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
              href="/guest">Buku Tamu</a>
          </li>
        </ul>

        <ul class="flex items-center gap-2 shrink-0">
          <li>
            <button
              x-cloak
              aria-hidden="true"
              @click="toggleDarkMode()"
              class="px-4 py-2.5 mt-2 text-sm font-semibold transition duration-300 bg-transparent rounded-lg dark:bg-transparent dark:hover:bg-gray-600 dark:focus:bg-gray-600 dark:focus:text-white dark:hover:text-white dark:text-gray-200 md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
            >
              <x-icons.sun x-show="!darkMode" />
              <x-icons.moon x-show="darkMode" />
            </button>
          </li>
          <li>
            <form
              action="/"
              x-data="{
                searchQuery: '{{ request('q') }}',
                clearSearch() { this.searchQuery = '' }
              }"
              class="relative text-gray-600"
            >
              <input
                type="text" name="q" placeholder="Search" x-model="searchQuery"
                autocomplete="off"
                class="px-5 pr-16 text-sm bg-white rounded-lg h-9 dark:border-0 w-50 focus:outline-none focus:ring-0"
                />
              @if (request('s'))
                <input type="hidden" name="s" placeholder="Search" value="{{request('s')}}"/>    
              @endif
              <button type="submit" class="absolute flex items-center gap-2 right-3 top-2.5">
                <x-icons.search class="w-4 h-4 text-gray-600 fill-current"/>
              </button>
              <button x-show="searchQuery" x-cloak type="submit" class="absolute flex items-center gap-2 right-8 top-2.5" @keydown.enter="clearSearch()" @click="clearSearch()">
                <x-icons.delete class="w-4 h-4 text-red-600 fill-current"/>
              </button>
            </form>
          </li>
          @guest
            <li>
              <a class="px-4 py-2.5 mt-2 text-sm font-semibold bg-transparent rounded-lg dark:bg-transparent dark:hover:bg-gray-600 dark:focus:bg-gray-600 dark:focus:text-white dark:hover:text-white dark:text-gray-200 md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
              href="/guest">Login</a>
            </li>
            <li>
              <a class="px-4 py-2.5 mt-2 text-sm font-semibold bg-transparent rounded-lg dark:bg-transparent dark:hover:bg-gray-600 dark:focus:bg-gray-600 dark:focus:text-white dark:hover:text-white dark:text-gray-200 md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
              href="/guest">Registrasi</a>
            </li>
          @endguest
          @auth
            <li @click.away="open = false" class="relative" x-data="{ open: false }">
              <button @click="open = !open" class="flex flex-row items-center w-full px-4 py-2.5 mt-2 text-sm font-semibold text-left text-gray-900 bg-transparent bg-gray-200 rounded-lg dark:bg-transparent dark:text-white dark:focus:text-white dark:hover:text-white dark:focus:bg-gray-600 dark:hover:bg-gray-600 md:w-auto md:inline md:mt-0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                <span>{{auth()->user()?->name}}</span>
                <svg fill="currentColor" viewBox="0 0 20 20" :class="{ 'rotate-180': open, 'rotate-0': !open }" class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
              </button>
              <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 z-50 w-full mt-2 origin-top-right md:max-w-screen-sm md:w-screen"
              >
                <div class="px-2 pt-2 pb-4 bg-white rounded-md shadow-lg dark:bg-gray-700">
                  <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <a class="flex items-start p-2 bg-transparent rounded-lg row dark:hover:bg-gray-600 dark:focus:bg-gray-600 dark:focus:text-white dark:hover:text-white dark:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
                      href="#">
                      <div class="p-3 text-white bg-teal-500 rounded-lg">
                          <svg fill="none" stroke="currentColor" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                              class="w-4 h-4 md:h-6 md:w-6">
                              <path
                                  d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                              </path>
                          </svg>
                      </div>
                      <div class="ml-3">
                          <p class="font-semibold">Appearance</p>
                          <p class="text-sm">Easy customization</p>
                      </div>
                    </a>
    
                    <a class="flex items-start p-2 bg-transparent rounded-lg row dark:hover:bg-gray-600 dark:focus:bg-gray-600 dark:focus:text-white dark:hover:text-white dark:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
                      href="#">
                      <div class="p-3 text-white bg-teal-500 rounded-lg">
                          <svg fill="none" stroke="currentColor" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                              class="w-4 h-4 md:h-6 md:w-6">
                              <path
                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                              </path>
                          </svg>
                      </div>
                      <div class="ml-3">
                          <p class="font-semibold">Comments</p>
                          <p class="text-sm">Check your latest comments</p>
                      </div>
                    </a>
    
                    <a class="flex items-start p-2 bg-transparent rounded-lg row dark:hover:bg-gray-600 dark:focus:bg-gray-600 dark:focus:text-white dark:hover:text-white dark:text-gray-200 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline"
                      href="#">
                      <div class="p-3 text-white bg-teal-500 rounded-lg">
                          <svg fill="none" stroke="currentColor" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                              class="w-4 h-4 md:h-6 md:w-6">
                              <path d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                              <path d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                          </svg>
                      </div>
                      <div class="ml-3">
                          <p class="font-semibold">Analytics</p>
                          <p class="text-sm">Take a look at your statistics</p>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </li>
          @endauth
        </div>
      </nav>
    </div>
  </div>
</div>
