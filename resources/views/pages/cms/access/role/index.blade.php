<x-layouts.cms.main title="Role Management">
  <x-cms.title title="Role Management" subtitle="Role on {{env('APP_NAME')}}"/>
    
  <div class="mt-8 flex justify-between items-stretch">
    <div>
      <a href="{{route('cms.access.role.create')}}" class="h-full inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
          <span class="sr-only">Create Role</span>
          Create Role
      </a>
    </div>
    <form action="{{route('cms.access.role.index')}}" method="GET" class="relative">
      @if (request('size'))
          <input type="hidden" value="{{request('size')}}" name="size">
      @endif
      @if (request('column'))
          <input type="hidden" value="{{request('column')}}" name="column">
      @endif
      @if (request('order'))
          <input type="hidden" value="{{request('order')}}" name="order">
      @endif
      <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
      </div>
      <input type="search" name="search" value="{{request('search')}}" id="table-search" class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for items">
    </form>
  </div>
  <div class="overflow-x-auto sm:rounded-lg mt-5">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th scope="col" class="px-6 py-3">
            <div class="flex items-center gap-2">
              Role Name
              <a href="{{request()->url()}}?{{request()->get('size') ? 'size='.request()->get('size').'&' : null}}column=name&order={{ (request()->get('column') == 'name' && request()->get('order') == 'desc') ? 'asc':'desc'}}{{request()->get('search') ? '&search='.request()->get('search') : null}}">
                <x-icons.chevron-down-icon class="{{ request()->get('column') == 'name' ? 'fill-blue-500':'' }} {{ (request()->get('column') == 'name' && request()->get('order') == 'desc') ? 'rotate-180':'' }}"/>
              </a>
            </div>
          </th>
          <th scope="col" class="px-6 py-3">
            <div class="flex items-center justify-center">
              Permission
            </div>
          </th>
          <th scope="col" class="px-6 py-3">
            <div class="flex items-center justify-center">
              Guard
            </div>
          </th>
          <th scope="col" class="px-6 py-3">
            <div class="flex items-center justify-center">
              Action
            </div>
          </th>
        </tr>
      </thead>
      <tbody>
        @forelse ($roles as $role)
          <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
              <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  {{$role->name}}
              </td>

              <td scope="row" class="text-center px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  @forelse ( $role->permissions->pluck('name') as $permission )
                    <span>{{$permission}}</span>,
                  @empty
                    @if($role->name == App\Models\Role::SUPER_ADMIN)
                      <span class="text-green-500">All Granted</span>
                    @else
                      &dash;
                    @endif
                  @endforelse
              </td>

              <td scope="row" class="text-center px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  {{$role->guard_name}}
              </td>
              
              <td class="flex justify-center items-center px-6 py-4 space-x-3">
                <a href="{{route('cms.access.role.edit', $role)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                <form action="{{route('cms.access.role.delete', $role)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove</button>
                </form>
            </td>
          </tr>
        @empty
          <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            <td scope="row" class="row-span-full text-center font-semibold p-3">No Data</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    {{$roles->links('components.cms.table.pagination')}}
  </div>
</x-layouts.cms.main>