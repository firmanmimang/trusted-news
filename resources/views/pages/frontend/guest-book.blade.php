@push('cssNduwur')
<style>
  input[type='number']::-webkit-inner-spin-button,
  input[type='number']::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
</style>
@endpush

<x-layouts.frontend.main title="Buku Tamu">
  <section class="container py-10 px-6 mx-auto sm:px-12">
     <div class="relative z-10 flex flex-col items-start mt-8 mb-16 mx-auto w-full lg:w-6/12">
         <div class="bg-white dark:bg-gray-700 shadow-2xl rounded-xl w-full p-6">
            <h1 class="text-2xl font-bold text-center pb-4 text-primary dark:text-white">Masukan anda sangat berarti buat kami</h1>
            <form action="{{route('guest.store')}}" method="POST">
              @csrf
              <div class="mb-6 flex flex-col gap-1 justify-start items-start">
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
              <div class="mb-6 flex flex-col gap-1 justify-start items-start">
                <x-frontend.input value="{{old('umur')}}" type="number" name="umur" placeholder="Masukan umur">
                    <x-slot:icon>
                      <x-icons.heart-beat class="fill-gray-400" width="20" height="20"/>
                    </x-slot:icon>
                </x-frontend.input>
                @error('umur')
                    <small class="text-red-500">
                    {{$message}}
                    </small>
                @enderror
              </div>
              <div class="mb-6 flex flex-col gap-1 justify-start items-start">
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
              <div class="mb-6 flex flex-col gap-1 justify-start items-start">
                <x-frontend.input value="{{old('nomor_telepon')}}" type="text" name="nomor_telepon" placeholder="Masukan nomor telepon">
                    <x-slot:icon>
                      <x-icons.telephone class="fill-gray-400" width="20" height="20"/>
                    </x-slot:icon>
                </x-frontend.input>
                @error('nomor_telepon')
                    <small class="text-red-500">
                    {{$message}}
                    </small>
                @enderror
              </div>
              <div class="mb-6 flex flex-col gap-1 justify-start items-start">
                <x-frontend.textarea value="{{old('pesan_dan_saran')}}" type="text" name="pesan_dan_saran" placeholder="Masukan pesan dan saran (optional)">
                    <x-slot:icon>
                      <x-icons.chat-square class="fill-gray-400" width="20" height="20"/>
                    </x-slot:icon>
                </x-frontend.textarea>
                @error('pesan_dan_saran')
                    <small class="text-red-500">
                    {{$message}}
                    </small>
                @enderror
              </div>
              <div class="w-full mb-6">
                <div class="grid grid-cols-2 w-full">
                  <div class="radio w-full">
                    <input name="kelamin" type="radio" id="laki" class="hidden peer" @checked(old('kelamin') == 'male') value="male"/>
                    <label for="laki" class="peer-checked:bg-primary bg-gray-300 dark:bg-gray-500 cursor-pointer w-full px-2 py-3 rounded-l-2xl flex justify-center items-center text-white transition-all duration-300">
                      Laki-Laki
                    </label>
                  </div>
                  <div class="inline-block radio">
                    <input name="kelamin" type="radio" id="perempuan" class="hidden peer" @checked(old('kelamin') == 'female') value="female"/>
                    <label for="perempuan" class="peer-checked:bg-primary bg-gray-300 dark:bg-gray-500 cursor-pointer w-full px-2 py-3 rounded-r-2xl flex justify-center items-center text-white transition-all duration-300">
                      Perempuan
                    </label>
                  </div>
                </div>
                @error('kelamin')
                    <small class="text-red-500">
                    {{$message}}
                    </small>
                @enderror
              </div>
              <div class='mb-6'>
                <div x-data="{ rating: {{old('rating')??0}} }" class="flex flex-row justify-center gap-3">
                  <input x-model="rating" name="rating" type="radio" id="rate1" class="hidden" @checked(old('rating') == 1) value="1"/>
                  <input x-model="rating" name="rating" type="radio" id="rate2" class="hidden" @checked(old('rating') == 2) value="2"/>
                  <input x-model="rating" name="rating" type="radio" id="rate3" class="hidden" @checked(old('rating') == 3) value="3"/>
                  <input x-model="rating" name="rating" type="radio" id="rate4" class="hidden" @checked(old('rating') == 4) value="4"/>
                  <input x-model="rating" name="rating" type="radio" id="rate5" class="hidden" @checked(old('rating') == 5) value="5"/>
                  <label for="rate1">
                    <svg :class="{ 'fill-yellow-200': rating >= 1 }" class="h-12 transition-all duration-100 fill-gray-400 cursor-pointer" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <path d="M575.852903 115.426402L661.092435 288.054362c10.130509 20.465674 29.675227 34.689317 52.289797 37.963825l190.433097 27.62866c56.996902 8.288598 79.7138 78.281203 38.475467 118.496253l-137.836314 134.35715c-16.372539 15.963226-23.84251 38.987109-19.954032 61.49935l32.540421 189.716799c9.721195 56.792245-49.833916 100.077146-100.793444 73.267113L545.870691 841.446188a69.491196 69.491196 0 0 0-64.67153 0l-170.376737 89.537324c-50.959528 26.810033-110.51464-16.474868-100.793444-73.267113L242.569401 667.9996c3.888478-22.512241-3.581493-45.536125-19.954032-61.49935L84.779055 472.245428c-41.238333-40.215049-18.521435-110.207655 38.475467-118.496252l190.433097-27.62866c22.61457-3.274508 42.159288-17.498151 52.289797-37.963826L451.319277 115.426402c25.479764-51.675827 99.053862-51.675827 124.533626 0z"/>
                    </svg>
                  </label>
                  <label for="rate2">
                    <svg :class="{ 'fill-yellow-200': rating >= 2 }" class="rating h-12 transition-all duration-100 fill-gray-400 cursor-pointer" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <path d="M575.852903 115.426402L661.092435 288.054362c10.130509 20.465674 29.675227 34.689317 52.289797 37.963825l190.433097 27.62866c56.996902 8.288598 79.7138 78.281203 38.475467 118.496253l-137.836314 134.35715c-16.372539 15.963226-23.84251 38.987109-19.954032 61.49935l32.540421 189.716799c9.721195 56.792245-49.833916 100.077146-100.793444 73.267113L545.870691 841.446188a69.491196 69.491196 0 0 0-64.67153 0l-170.376737 89.537324c-50.959528 26.810033-110.51464-16.474868-100.793444-73.267113L242.569401 667.9996c3.888478-22.512241-3.581493-45.536125-19.954032-61.49935L84.779055 472.245428c-41.238333-40.215049-18.521435-110.207655 38.475467-118.496252l190.433097-27.62866c22.61457-3.274508 42.159288-17.498151 52.289797-37.963826L451.319277 115.426402c25.479764-51.675827 99.053862-51.675827 124.533626 0z" />
                    </svg>
                  </label>
                  <label for="rate3">
                    <svg :class="{ 'fill-yellow-200': rating >= 3 }" class="rating h-12 transition-all duration-100 fill-gray-400 cursor-pointer" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <path d="M575.852903 115.426402L661.092435 288.054362c10.130509 20.465674 29.675227 34.689317 52.289797 37.963825l190.433097 27.62866c56.996902 8.288598 79.7138 78.281203 38.475467 118.496253l-137.836314 134.35715c-16.372539 15.963226-23.84251 38.987109-19.954032 61.49935l32.540421 189.716799c9.721195 56.792245-49.833916 100.077146-100.793444 73.267113L545.870691 841.446188a69.491196 69.491196 0 0 0-64.67153 0l-170.376737 89.537324c-50.959528 26.810033-110.51464-16.474868-100.793444-73.267113L242.569401 667.9996c3.888478-22.512241-3.581493-45.536125-19.954032-61.49935L84.779055 472.245428c-41.238333-40.215049-18.521435-110.207655 38.475467-118.496252l190.433097-27.62866c22.61457-3.274508 42.159288-17.498151 52.289797-37.963826L451.319277 115.426402c25.479764-51.675827 99.053862-51.675827 124.533626 0z" />
                    </svg>
                  </label>
                  <label for="rate4">
                    <svg :class="{ 'fill-yellow-200': rating >= 4 }" class="rating h-12 transition-all duration-100 fill-gray-400 cursor-pointer" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <path d="M575.852903 115.426402L661.092435 288.054362c10.130509 20.465674 29.675227 34.689317 52.289797 37.963825l190.433097 27.62866c56.996902 8.288598 79.7138 78.281203 38.475467 118.496253l-137.836314 134.35715c-16.372539 15.963226-23.84251 38.987109-19.954032 61.49935l32.540421 189.716799c9.721195 56.792245-49.833916 100.077146-100.793444 73.267113L545.870691 841.446188a69.491196 69.491196 0 0 0-64.67153 0l-170.376737 89.537324c-50.959528 26.810033-110.51464-16.474868-100.793444-73.267113L242.569401 667.9996c3.888478-22.512241-3.581493-45.536125-19.954032-61.49935L84.779055 472.245428c-41.238333-40.215049-18.521435-110.207655 38.475467-118.496252l190.433097-27.62866c22.61457-3.274508 42.159288-17.498151 52.289797-37.963826L451.319277 115.426402c25.479764-51.675827 99.053862-51.675827 124.533626 0z" />
                    </svg>
                  </label>
                  <label for="rate5">
                    <svg :class="{ 'fill-yellow-200': rating >= 5 }" class="rating h-12 transition-all duration-100 fill-gray-400 cursor-pointer" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <path d="M575.852903 115.426402L661.092435 288.054362c10.130509 20.465674 29.675227 34.689317 52.289797 37.963825l190.433097 27.62866c56.996902 8.288598 79.7138 78.281203 38.475467 118.496253l-137.836314 134.35715c-16.372539 15.963226-23.84251 38.987109-19.954032 61.49935l32.540421 189.716799c9.721195 56.792245-49.833916 100.077146-100.793444 73.267113L545.870691 841.446188a69.491196 69.491196 0 0 0-64.67153 0l-170.376737 89.537324c-50.959528 26.810033-110.51464-16.474868-100.793444-73.267113L242.569401 667.9996c3.888478-22.512241-3.581493-45.536125-19.954032-61.49935L84.779055 472.245428c-41.238333-40.215049-18.521435-110.207655 38.475467-118.496252l190.433097-27.62866c22.61457-3.274508 42.159288-17.498151 52.289797-37.963826L451.319277 115.426402c25.479764-51.675827 99.053862-51.675827 124.533626 0z" />
                    </svg>
                  </label>
                </div>
                @error('rating')
                    <small class="text-red-500">
                    {{$message}}
                    </small>
                @enderror
              </div>
              <div>
                <button type="submit" class="w-full p-3 text-white transition border rounded-2xl bg-primary border-primary hover:bg-opacity-90">
                  Kirim Pesan
                </button>
              </div>
            </form>
         </div>
  </section>
</x-layouts.frontend.main>