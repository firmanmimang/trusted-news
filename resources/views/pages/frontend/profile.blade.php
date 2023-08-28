<x-layouts.frontend.main title="Berita Terbaru">
  <section class="grid w-full grid-cols-5 gap-5 px-5 py-10 m-auto">
    {{-- session active --}}
    <div class="col-span-2 sticky z-30 w-full p-6 flex flex-col gap-4 bg-white shadow-2xl h-max top-22.5 dark:bg-gray-700 rounded-xl">
      <h2 class="text-2xl font-bold text-center">Sessi Aktif</h2>
      <turbo-frame id="session" class="flex flex-col gap-4">
        @foreach ($active_session as $session)
          <div class="p-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl flex justify-between">
            <div class="grow">
              <table>
                <tr>
                  <td>Ip Addres</td>
                  <td>:</td>
                  <td>
                    <p>{{$session->ip_address}}</p>
                  </td>
                </tr>
                <tr>
                  <td>User Agent</td>
                  <td>:</td>
                  <td>
                    <p>{{$session->user_agent_detected}}</p>
                  </td>
                </tr>
                <tr>
                  <td>Expires</td>
                  <td>:</td>
                  <td>
                    <p>{{$session->expires_at}}</p>
                  </td>
                </tr>
                <tr>
                  <td>Last visit</td>
                  <td>:</td>
                  <td>
                    <p>{{\App\Helpers\ParseUrlHelper::parseUrl($session->payload_decode['_previous']['url'])}}</p>
                  </td>
                </tr>
              </table>
            </div>
            <div class="">
              <div class="flex items-center gap-2 flex-col justify-center">
                @if($session->payload_decode['_token'] === csrf_token())
                  <p>Your session</p>
                @endif
                @if (!$session->isExpired())
                  <span class="block text-center text-success">Aktif</span>
                  @if(!($session->payload_decode['_token'] === csrf_token()))
                    <form action="{{route('profile.session.terminate', ['token'=> $session->payload])}}"
                      method="POST"
                      x-data="{
                        modelOpen: false,
                        focus: function() {
                          const textInput = this.$refs.btnBatalModal;
                          textInput.focus();
                        }
                      }"
                      >
                      @csrf
                      @method('PUT')
                      <button @click.prevent="modelOpen = true; $nextTick(() => focus()) " class="px-3 py-2 text-white transition border rounded-2xl bg-primary border-primary hover:bg-opacity-90 disabled:bg-blue-400 disabled:border-blue-400">
                        Terminate
                      </button>
                      {{-- modal konfirmasi --}}
                      <div x-show="modelOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-delete" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                            <div x-cloak @click="modelOpen = false" x-show="modelOpen"
                                x-transition:enter="transition ease-out duration-300 transform"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200 transform"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-40" aria-hidden="true"
                            ></div>
                            <div x-cloak x-show="modelOpen"
                                x-transition:enter="transition ease-out duration-300 transform"
                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave="transition ease-in duration-200 transform"
                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                class="inline-block max-w-xl p-4 text-left transition-all transform bg-white rounded-lg shadow mb-59 md:mt-59 md:mb-0 h-max dark:bg-gray-700 w-max 2xl:max-w-2xl"
                            >
                                <div class="flex items-center justify-end space-x-4">
                                    <button @click="modelOpen = false" class="text-gray-600 transition duration-300 focus:outline-none hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="p-3">
                                    <p class="text-base leading-relaxed text-center text-primary dark:text-gray-400">
                                      Terminate sessi ini.
                                    </p>
                                </div>
                                <div class="flex items-center justify-end p-3 space-x-2 rounded-b">
                                  <button x-ref="btnBatalModal" @click="modelOpen = false" type="button" class="px-3 py-1 text-sm font-medium text-gray-500 transition duration-300 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 focus:outline-none">Batal</button>
                                  <button type="submit" class="px-3 py-1 text-white transition border rounded-lg bg-primary border-primary hover:bg-opacity-90 disabled:bg-blue-400 disabled:border-blue-400">
                                    Terminate
                                  </button>
                                </div>
                            </div>
                        </div>
                      </div>
                    </form>
                  @endif
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </turbo-frame>
    </div>
    <div class="col-span-2">
      {{-- profile --}}
      <div class="w-full p-6 mb-6 bg-white shadow-2xl dark:bg-gray-700 rounded-xl">
        <h1 class="pb-4 text-2xl font-bold text-center" id="profile">Profile Kamu</h1>
        @if(auth()->user()->hasPermissionTo('change password'))
          <form action="{{route('profile.update')}}"
            method="POST"
            enctype="multipart/form-data"
            x-data="{
              form: {
                name: '{{auth()->user()->name}}',
                username: '{{auth()->user()->username}}',
                photo_profile: '{{auth()->user()->imageImage}}',
              },
              handleInputImage(e){
                if(e.target.files[0]){
                  this.form.photo_profile = URL.createObjectURL(e.target.files[0])
                }else{
                  this.form.photo_profile = '{{auth()->user()->imageImage}}'
                }
              },
              isButtonDisabled: true,
            }
            "
            x-init="
              $watch('form', () => {
                if(form.name != '{{auth()->user()->name}}' || form.username != '{{auth()->user()->username}}' || form.photo_profile != '{{auth()->user()->imageImage}}'){
                  isButtonDisabled = false
                } else {
                  console.log('dis')
                  isButtonDisabled = true
                }
              })
            "
            >
            @csrf
            @method('PUT')
            <div class="flex flex-col items-center justify-start gap-1 mb-6">
              <label for="photo-profile" class="cursor-pointer">
                <div class="w-20 h-20 overflow-hidden rounded-full">
                  <img :src="form.photo_profile" x-cloak alt="photo profile" class="object-cover w-full h-full">
                </div>
                <input @input="handleInputImage" type="file" name="photo_profile" id="photo-profile" hidden>
              </label>
              @error('photo_profile')
                  <small class="text-red-500">
                  {{$message}}
                  </small>
              @enderror
            </div>
            <div class="flex flex-col items-start justify-start gap-1 mb-6">
              <x-frontend.input x-model="form.name" value="{{auth()->user()->name}}" type="text" name="nama" placeholder="Masukan nama">
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
            <div class="flex flex-col items-start justify-start gap-1 mb-6">
              <x-frontend.input x-model="form.username" value="{{auth()->user()->username}}" type="text" name="username" placeholder="Masukan username">
                  <x-slot:icon>
                    <x-icons.person-circle class="fill-gray-400" width="20" height="20"/>
                  </x-slot:icon>
              </x-frontend.input>
              @error('username')
                  <small class="text-red-500">
                  {{$message}}
                  </small>
              @enderror
            </div>
            <div class="flex flex-col items-start justify-start gap-1 mb-6">
              <x-frontend.input :disabled="true" value="{{auth()->user()->email}}" type="email" name="email" placeholder="Masukan email">
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
            <div>
              <button :disabled="isButtonDisabled" type="submit" class="w-full p-3 text-white transition border rounded-2xl bg-primary border-primary hover:bg-opacity-90 disabled:bg-blue-400 disabled:border-blue-400">
                Update
              </button>
            </div>
          </form>
        @else
          <p class="text-lg font-semibold text-center uppercase text-danger">
            akses update profile kamu sedang ditangguhkan. <br>
            <span class="text-gray-600 dark:text-gray-400">hubungi <a href="{{route('contact')}}" class="dark:text-blue-500 text-primary">Trusted News</a> untuk info lebih lanjut</span>
          </p>
        @endif
      </div>
  
      {{-- change password --}}
      <div class="w-full p-6 bg-white shadow-2xl dark:bg-gray-700 rounded-xl">
        <h2 class="pb-4 text-2xl font-bold text-center">
          @if( !old('password_baru') )
            Setup Password
          @else
            Ganti Password
          @endif
        </h2>
        @if(auth()->user()->hasPermissionTo('change password'))
          <form action="{{route('profile.update.password')}}"
            method="POST"
            @if (auth()->user()->password)
              x-data="{
                formChangePassword: {
                  password_sekarang: '{{old("password_sekarang")}}',
                  password_baru: '{{old("password_baru")}}',
                  password_baru_confirmation: '{{old("password_baru_confirmation")}}',
                },
                isButtonDisabledPassword: true,
                focus: function() {
                  const textInput = this.$refs.btnBatalModal;
                  textInput.focus();
                }
              }
              "
              x-init="
                if(formChangePassword.password_sekarang != '' && formChangePassword.password_baru != '' && formChangePassword.password_baru_confirmation != ''){
                  isButtonDisabledPassword = false
                } else {
                  isButtonDisabledPassword = true
                }
                $watch('formChangePassword', () => {
                  if(formChangePassword.password_sekarang != '' && formChangePassword.password_baru != '' && formChangePassword.password_baru_confirmation != ''){
                    console.log('exist', isButtonDisabledPassword)
                    isButtonDisabledPassword = false
                  } else {
                    isButtonDisabledPassword = true
                  }
                })
              "
            @else
              x-data="{
                formChangePassword: {
                  password_baru: '{{old("password_baru")}}',
                  password_baru_confirmation: '{{old("password_baru_confirmation")}}',
                },
                isButtonDisabledPassword: true,
              }
              "
              x-init="
                if(formChangePassword.password_baru != '' && formChangePassword.password_baru_confirmation != ''){
                  isButtonDisabledPassword = false
                } else {
                  isButtonDisabledPassword = true
                }
                $watch('formChangePassword', () => {
                  if(formChangePassword.password_baru != '' && formChangePassword.password_baru_confirmation != ''){
                    isButtonDisabledPassword = false
                  } else {
                    isButtonDisabledPassword = true
                  }
                })
              "
            @endif
            >
            @csrf
            @method('PUT')
            @if(auth()->user()->password)
              <div class="flex flex-col items-start justify-start gap-1 mb-6">
                <x-frontend.input x-model="formChangePassword.password_sekarang" value="{{old('password_sekarang')}}" :toggle="true" type="password" name="password_sekarang" placeholder="Masukan password sekarang">
                    <x-slot:icon>
                      <x-icons.key class="fill-gray-400" width="20" height="20"/>
                    </x-slot:icon>
                </x-frontend.input>
                @error('password_sekarang')
                    <small class="text-red-500">
                    {{$message}}
                    </small>
                @enderror
              </div>
            @endif
            <div class="flex flex-col items-start justify-start gap-1 mb-6">
              <x-frontend.input x-model="formChangePassword.password_baru" value="{{old('password_baru')}}" :toggle="true" type="password" name="password_baru" placeholder="Masukan password baru">
                  <x-slot:icon>
                    <x-icons.key class="fill-gray-400" width="20" height="20"/>
                  </x-slot:icon>
              </x-frontend.input>
              @error('password_baru')
                  <small class="text-red-500">
                  {{$message}}
                  </small>
              @enderror
            </div>
            <div class="flex flex-col items-start justify-start gap-1 mb-6">
              <x-frontend.input x-model="formChangePassword.password_baru_confirmation" value="{{old('password_baru_confirmation')}}" :toggle="true" type="password" name="password_baru_confirmation" placeholder="Masukan konfirmasi password baru">
                  <x-slot:icon>
                    <x-icons.key class="fill-gray-400" width="20" height="20"/>
                  </x-slot:icon>
              </x-frontend.input>
              @error('password_baru_confirmation')
                  <small class="text-red-500">
                  {{$message}}
                  </small>
              @enderror
            </div>
            <div x-data="{ 
              modelOpen: false,
              focus: function() {
                const textInput = this.$refs.btnBatalModal;
                textInput.focus();
              },
            }">
              <button @if(!old('password_baru')) @click.prevent="modelOpen = true; $nextTick(() => focus()) " @endif :disabled="isButtonDisabledPassword" class="w-full p-3 text-white transition border rounded-2xl bg-primary border-primary hover:bg-opacity-90 disabled:bg-blue-400 disabled:border-blue-400">
                Kirim
              </button>
              {{-- modal konfirmasi --}}
              @if( !old('password_baru') )
                <div x-show="modelOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-delete" role="dialog" aria-modal="true">
                  <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                      <div x-cloak @click="modelOpen = false" x-show="modelOpen"
                          x-transition:enter="transition ease-out duration-300 transform"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          x-transition:leave="transition ease-in duration-200 transform"
                          x-transition:leave-start="opacity-100"
                          x-transition:leave-end="opacity-0"
                          class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-40" aria-hidden="true"
                      ></div>
                      <div x-cloak x-show="modelOpen"
                          x-transition:enter="transition ease-out duration-300 transform"
                          x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                          x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                          x-transition:leave="transition ease-in duration-200 transform"
                          x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                          x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                          class="inline-block max-w-xl p-4 text-left transition-all transform bg-white rounded-lg shadow mb-59 md:mt-59 md:mb-0 h-max dark:bg-gray-700 w-max 2xl:max-w-2xl"
                      >
                          <div class="flex items-center justify-end space-x-4">
                              <button @click="modelOpen = false" class="text-gray-600 transition duration-300 focus:outline-none hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                  </svg>
                              </button>
                          </div>
                          <div class="p-3">
                              <p class="text-base leading-relaxed text-center text-primary dark:text-gray-400">
                                Dengan mengganti password kamu, <br> akan menyebabkan semua sesi login kamu saat ini diakhiri.
                              </p>
                          </div>
                          <div class="flex items-center justify-end p-3 space-x-2 rounded-b">
                            <button x-ref="btnBatalModal" @click="modelOpen = false" type="button" class="px-3 py-1 text-sm font-medium text-gray-500 transition duration-300 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 focus:outline-none">Batal</button>
                            <button :disabled="isButtonDisabledPassword" type="submit" class="px-3 py-1 text-white transition border rounded-lg bg-primary border-primary hover:bg-opacity-90 disabled:bg-blue-400 disabled:border-blue-400">
                              Lanjutkan
                            </button>
                          </div>
                      </div>
                  </div>
                </div>
              @endif
            </div>
          </form>
        @else
          <p class="text-lg font-semibold text-center uppercase text-danger">
            akses ganti password kamu sedang ditangguhkan. <br>
            <span class="text-gray-600 dark:text-gray-400">hubungi <a href="{{route('contact')}}" class="dark:text-blue-500 text-primary">Trusted News</a> untuk info lebih lanjut</span>
          </p>
        @endif
      </div>
    </div>
  </section>
</x-layouts.frontend.main>
