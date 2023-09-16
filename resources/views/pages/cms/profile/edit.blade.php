<x-layouts.cms.main title="Dashboard">
  <x-cms.title title="Edit Profile" subtitle="Profile {{auth()->user()->name}}"/>
  <form novalidate action="{{route('cms.profile.update')}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <label for="photo-profile" class="cursor-pointer" x-data="{
      form: {
        avatar: '{{old('avatar', auth()->user()->imageImage)}}',
      },
      handleInputImage(e){
        if(e.target.files[0]){
          this.form.avatar = URL.createObjectURL(e.target.files[0])
        }else{
          this.form.avatar = '{{old('avatar', auth()->user()->imageImage)}}'
        }
      },
    }
    ">
      <div class="w-20 h-20 m-auto overflow-hidden rounded-full form-control-ff">
        <img :src="form.avatar" x-cloak alt="photo profile" class="object-cover w-full h-full">
      </div>
      <input @input="handleInputImage" type="file" name="avatar" id="photo-profile" hidden>
      @if($errors->first('avatar')) <p class="text-center mt-2 text-sm text-red-700 dark:text-red-500">{{$errors->first('avatar')}}</p> @endif
    </label>
    <x-cms.textfield error="{{$errors->first('name')}}" value="{{old('name', auth()->user()->name)}}" type="text" name="name" :required="true" id="name" label="Name" placeholder="Your Name">
      <x-slot:icon>
        <x-icons.person-circle class="w-4 h-4 text-gray-500 dark:text-gray-400"/>
      </x-slot:icon>
    </x-cms.textfield>
    <x-cms.textfield error="{{$errors->first('username')}}" value="{{old('username', auth()->user()->username)}}" type="text" name="username" :required="true" id="username" label="Username" placeholder="Your Username">
      <x-slot:icon>
        <x-icons.person-circle class="w-4 h-4 text-gray-500 dark:text-gray-400"/>
      </x-slot:icon>
    </x-cms.textfield>
    <x-cms.textfield value="{{auth()->user()->email}}" type="email" name="name" :disabled="true" :required="true" id="email" label="Email" placeholder="Your Email">
      <x-slot:icon>
        <x-icons.envelope class="w-4 h-4 text-gray-500 dark:text-gray-400"/>
      </x-slot:icon>
    </x-cms.textfield>

    <button type="submit" class="text-white form-control-ff bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
  </form>

</x-layouts.cms.main>