<x-layouts.cms.main title="Create Role">
  <x-cms.title title="Create Role" subtitle="New Role"/>
  <form action="{{route('cms.access.role.store')}}" method="POST">
    @csrf
    <x-cms.textfield error="{{$errors->first('name')}}" value="{{old('name')}}" type="text" name="name" :required="true" id="name" label="Name" placeholder="Role Name" />

    <x-cms.multi-select error="{{$errors->first('guard')}}" id="guard" name="guard" label="Guard">
      <x-slot:option>
        @foreach ( $guard as $item)
          <option value="{{$item}}">{{$item}}</option>
        @endforeach
      </x-slot:option>
    </x-cms.multi-select>

    <x-cms.multi-select error="{{$errors->first('permission')}}" id="permission" name="permission[]" label="Permissions" :multiple="true">
      <x-slot:option>
        <option>Choose permission</option>
        @foreach ( $permissions as $permission)
          <option value="{{$permission->id}}">{{$permission->name}}</option>
        @endforeach
      </x-slot:option>
    </x-cms.multi-select>

    <button type="submit" class="text-white form-control-ff bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
  </form>

</x-layouts.cms.main>