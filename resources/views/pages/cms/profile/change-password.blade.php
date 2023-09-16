<x-layouts.cms.main title="Dashboard">
  <x-cms.title title="Change Password" subtitle="Profile {{auth()->user()->name}}"/>
  <form novalidate action="{{route('cms.profile.password.update')}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-cms.textfield
    :toggle="true"
    :required="true"
    value="{{old('new_password')}}"
    error="{{$errors->first('new_password')}}"
    name="new_password"
    type="password"
    id="new_password"
    label="New Password"
    placeholder="New Password"
    >
      <x-slot:icon>
        <x-icons.key class="w-4 h-4 text-gray-500 dark:text-gray-400"/>
      </x-slot:icon>
    </x-cms.textfield>
    <x-cms.textfield
    :toggle="true"
    :required="true"
    value="{{old('new_password_confirmation')}}"
    error="{{$errors->first('new_password_confirmation')}}"
    name="new_password_confirmation"
    type="password"
    id="new_password_confirmation"
    label="New Password Confirmation"
    placeholder="New Password Confirmation"
    >
      <x-slot:icon>
        <x-icons.key class="w-4 h-4 text-gray-500 dark:text-gray-400"/>
      </x-slot:icon>
    </x-cms.textfield>

    <button type="submit" class="text-white form-control-ff bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
  </form>

</x-layouts.cms.main>