<x-layouts.frontend.auth title="Register">
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

      <h1 class="pb-4 text-2xl font-bold text-center text-gray-900 dark:text-white">Register</h1>

      <form novalidate name="register" action="{{route('register.store', ['in' => request()->get('in')])}}" method="POST">
        @csrf
        <div class="flex flex-col items-start justify-start gap-1 mb-4">
          <x-frontend.input value="{{old('nama')}}" type="text" name="nama" placeholder="Masukan nama">
              <x-slot:icon>
                <x-icons.person-circle class="fill-gray-400" width="20" height="20"/>
              </x-slot:icon>
          </x-frontend.input>
          @error('nama')
              <small class="text-red-500">
              {{$message}}
              </small>
          @enderror
        </div>
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
        <div class="flex flex-col items-start justify-start gap-1 mb-4">
          <x-frontend.input :toggle="true" type="password" name="password_confirmation" placeholder="Masukan konfirmasi password">
              <x-slot:icon>
                <x-icons.key class="fill-gray-400" width="20" height="20"/>
              </x-slot:icon>
          </x-frontend.input>
          @error('password_confirmation')
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
          Sudah memiliki akun? <a href="{{route('login')}}" class="text-primary dark:text-blue-500">Login Sekarang</a>
        </p>
      </form>
    </div>
  </div>
</x-layouts.frontend.auth>