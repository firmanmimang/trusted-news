<x-layouts.cms.main title="Edit Category {{$category->name}}">
  <x-cms.title title="Edit Category" subtitle="Category {{$category->name}}"/>
  <form action="{{route('cms.category.update', $category)}}" method="POST">
    @csrf
    @method('PUT')

    <x-cms.textfield
      error="{{$errors->first('name')}}"
      value="{{old('name', $category->name)}}"
      type="text" name="name"
      :required="true"
      id="name"
      label="Name"
      placeholder="Role Name"
    />

    <button type="submit" class="text-white form-control-ff bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
  </form>

</x-layouts.cms.main>