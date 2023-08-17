<x-layouts.frontend.auth title="Login">
  <div class="flex items-center justify-center" style="height: 100vh;
    background-image: url('https://images.unsplash.com/photo-1504711434969-e33886168f5c?crop=entropy&cs=srgb&fm=jpg&ixid=Mnw3MjAxN3wwfDF8c2VhcmNofDJ8fG5ld3N8ZW58MHx8fHwxNjQ4NTQ0ODY3&ixlib=rb-1.2.1&q=85&q=85&fmt=jpg&crop=entropy&cs=tinysrgb&w=450');
    background-color: black;
    background-repeat: no-repeat;
    background-size: cover"
    >
    <div class="relative w-1/2 p-6 bg-white shadow-2xl dark:bg-gray-700 rounded-xl">

      <div class="absolute flex gap-2 top-7 left-7">
        <a href="{{ url()->previous() }}" class="flex items-center">
          <x-icons.chevron-left class="h-7"/>&nbsp;<span class="text-sm font-semibold">Back</span>
        </a>
      </div>

      <div class="absolute flex items-center gap-2 top-6 right-6">
        <button x-cloak aria-hidden="true" @click="toggleDarkMode()" class="hidden lg:block px-2.5 py-2.5 mt-2 text-sm font-semibold transition duration-300 bg-transparent rounded-lg dark:bg-transparent dark:hover:bg-gray-600 dark:focus:bg-gray-600 dark:focus:text-white dark:hover:text-white dark:text-gray-200 md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
            <x-icons.sun x-show="!darkMode" />
            <x-icons.moon x-show="darkMode" />
        </button>
        <a href="{{route('home')}}">
          <img src="/assets/image/Logo_T_huruf.png" alt="Logo Trusted News" title="Trusted News" class="object-contain h-7">
        </a>
      </div>

      <h1 class="pb-4 text-2xl font-bold text-center text-gray-900 dark:text-white">Login</h1>

      <form novalidate name="login" action="{{route('login.store', ['in' => request()->get('in')])}}" method="POST">
        @csrf
        <div class="flex flex-col items-start justify-start gap-1 mb-4">
          <x-frontend.input value="{{old('email')}}" type="email" name="email" placeholder="Masukan email">
              <x-slot:icon>
                <x-icons.envelope class="fill-gray-400" width="20" height="20"/>
              </x-slot:icon>
          </x-frontend.input>
          @error('email')
              <small class="text-red-500">
              {{$message}}
              </small>
          @enderror
        </div>
        <div class="flex flex-col items-start justify-start gap-1 mb-4">
          <x-frontend.input :toggle="true" type="password" name="password" placeholder="Masukan password">
              <x-slot:icon>
                <x-icons.key class="fill-gray-400" width="20" height="20"/>
              </x-slot:icon>
          </x-frontend.input>
          @error('password')
              <small class="text-red-500">
              {{$message}}
              </small>
          @enderror
        </div>
        <div>
          <button type="submit" class="w-full p-3 text-white transition border rounded-2xl bg-primary border-primary hover:bg-opacity-90">
            Kirim
          </button>
        </div>
        <p class="mt-2 text-center">
          Belum memiliki akun? <a href="{{route('register')}}" class="text-primary dark:text-blue-500">Daftar Sekarang</a>
        </p>
      </form>

      <hr class="my-4 border border-gray-300 dark:border-gray-600">

      <form name="google" action="{{route('login.socialite', ['provider' => \App\Models\Account::GOOGLE])}}" method="POST" data-turbo="false" class="mb-4">
        @csrf
        <button class="w-full px-6 py-3 transition duration-300 border-2 border-gray-300 group dark:border-gray-600 rounded-2xl hover:border-blue-400 focus:bg-blue-50 active:bg-blue-100 dark:active:bg-slate-800 dark:focus:bg-slate-800">
          <div class="relative flex items-center justify-center space-x-4">
            <x-icons.google class="absolute left-0 w-5" width="20" height="20"/>
            <span class="block text-sm font-semibold tracking-wide text-gray-700 transition duration-300 w-max dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 sm:text-base">
              Login dengan Google
            </span>
          </div>
        </button>
      </form>
      
      <form name="github" action="{{route('login.socialite', ['provider' => \App\Models\Account::GITHUB])}}" method="POST" data-turbo="false" class="mb-4">
        @csrf
        <button class="w-full px-6 py-3 transition duration-300 border-2 border-gray-300 group dark:border-gray-600 rounded-2xl hover:border-blue-400 focus:bg-blue-50 active:bg-blue-100 dark:active:bg-slate-800 dark:focus:bg-slate-800">
          <div class="relative flex items-center justify-center space-x-4">
            <x-icons.github class="absolute left-0 w-5" width="25" height="25"/>
            <span class="block text-sm font-semibold tracking-wide text-gray-700 transition duration-300 w-max dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 sm:text-base">
              Login dengan Github
            </span>
          </div>
        </button>
      </form>
    </div>
  </div>
</x-layouts.frontend.auth>