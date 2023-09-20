@push('javascriptNgisor')
  <script defer type="module">
    let eventSource = null;

    function stopEventSource() {
      if (eventSource) {
        eventSource.close();
      }
    }
    document.addEventListener('turbo:before-visit', stopEventSource);

    eventSource = new EventSource("{{route('cms.access.user.stream')}}");

    eventSource.onmessage = function(event) {
      if (event.data.includes('user_online')) {
        const value = JSON.parse(event.data)
        value.user_online.map((user, index) => {
          const elementStatus = document.getElementById(`userStatus${user.id}`)
          const elementTextStatus = document.getElementById(`userTextStatus${user.id}`)
          if (elementStatus && elementTextStatus) {
              if (user.online) {
                  elementStatus.classList.remove('!bg-gray-500');
                  elementStatus.classList.add('!bg-green-500');
                  elementTextStatus.textContent = 'Online';
              } else {
                  elementStatus.classList.remove('!bg-green-500');
                  elementStatus.classList.add('!bg-gray-500');
                  elementTextStatus.textContent = 'Offline';
              }
          }
        })
      }

    }
  </script>
@endpush
<x-layouts.cms.main title="User Management">
  <x-cms.title title="User Management" subtitle="User on {{env('APP_NAME')}}"/>
    
  <div class="mt-8 flex justify-between items-stretch">
    <div>
      <a href="{{route('cms.access.user.create')}}" class="h-full inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
          <span class="sr-only">Create User</span>
          Create User
      </a>
    </div>
    <form action="{{route('cms.access.user.index')}}" method="GET" class="relative">
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
              Name
              <a href="{{request()->url()}}?{{request()->get('size') ? 'size='.request()->get('size').'&' : null}}column=name&order={{ (request()->get('column') == 'name' && request()->get('order') == 'desc') ? 'asc':'desc'}}{{request()->get('search') ? '&search='.request()->get('search') : null}}">
                <x-icons.chevron-down-icon class="{{ request()->get('column') == 'name' ? 'fill-blue-500':'' }} {{ (request()->get('column') == 'name' && request()->get('order') == 'desc') ? 'rotate-180':'' }}"/>
              </a>
            </div>
          </th>
          <th scope="col" class="px-6 py-3">
            <div class="flex items-center justify-center">
              Role
            </div>
          </th>
          <th scope="col" class="px-6 py-3">
            <div class="flex items-center justify-center">
              Status
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
        @forelse ($users as $user)
          <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
              <td scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                <img class="w-10 h-10 rounded-full" src="{{$user->imageImage}}" alt="{{$user->name}} image">
                <div class="pl-3">
                    <div class="text-base font-semibold">{{$user->name}}</div>
                    <div class="font-normal text-gray-500">{{$user->email}}</div>
                    <div class="font-normal text-gray-500">username : {{$user->username}}</div>
                </div>  
              </td>

              <td scope="row" class="text-center px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  @forelse ( $user->roles->pluck('name') as $role )
                    <span>{{$role}}</span>,
                  @empty
                    &dash;
                  @endforelse
              </td>

              <td class="px-6 py-4">
                @if(Cache::has('user-online' . $user->id))
                  <div class="flex justify-center items-center">
                    <div class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2" id="userStatus{{$user->id}}"></div> <span id="userTextStatus{{$user->id}}">Online</span>
                  </div>
                @else
                  <div class="flex justify-center items-center">
                    <div class="h-2.5 w-2.5 rounded-full bg-gray-500 mr-2" id="userStatus{{$user->id}}"></div> <span id="userTextStatus{{$user->id}}">Offline</span>
                  </div>
                @endif
              </td>
              
              <td scope="row" class="text-center px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                <a href="{{route('cms.access.user.edit', $user)}}" class="block font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                <form action="{{route('cms.access.user.delete', $user)}}" method="post" class="text-center w-full flex items-center justify-center">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-center block font-medium text-red-600 dark:text-red-500 hover:underline">Remove</button>
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
    {{$users->links('components.cms.table.pagination')}}
  </div>
</x-layouts.cms.main>