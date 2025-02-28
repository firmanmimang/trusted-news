<x-layouts.cms.main title="News In House Management">
  <x-cms.title title="News In House Management" subtitle="News In House on {{env('APP_NAME')}}"/>
    
  <div class="flex items-stretch justify-between mt-8">
    <div>
      <a href="{{route('cms.news.in-house.create')}}" class="h-full inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
          <span class="sr-only">Create Post</span>
          Create Post
      </a>
    </div>
    <form action="{{route('cms.news.in-house.index')}}" method="GET" class="relative">
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
      <input type="search" name="search" value="{{request('search')}}" id="table-search" class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for news">
    </form>
  </div>
  <div class="mt-5 overflow-x-auto sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th scope="col" class="px-6 py-3">
            <div class="flex items-center gap-2">
              Title
              <a href="{{request()->url()}}?{{request()->get('size') ? 'size='.request()->get('size').'&' : null}}column=title&order={{ (request()->get('column') == 'title' && request()->get('order') == 'desc') ? 'asc':'desc'}}{{request()->get('search') ? '&search='.request()->get('search') : null}}">
                <x-icons.chevron-down-icon class="{{ request()->get('column') == 'title' ? 'fill-blue-500':'' }} {{ (request()->get('column') == 'title' && request()->get('order') == 'desc') ? 'rotate-180':'' }}"/>
              </a>
            </div>
          </th>
          <th scope="col" class="px-6 py-3">
            <div class="flex items-center justify-center">
              Category
            </div>
          </th>
          <th scope="col" class="px-6 py-3">
            <div class="flex items-center justify-center">
              Excerpt
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
        @forelse ($news as $item)
          <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
              <div class="pl-3">
                <div class="text-base font-semibold">{{$item->title}}</div>
                <div class="font-normal text-gray-500">slug : {{$item->slug}}</div>
                <div class="font-normal text-gray-500">author : {{$item->author?->name}}</div>
                <div class="font-normal text-gray-500">comments : {{$item->comments_count}}</div>
              </div>  
            </td>

            <td scope="row" class="px-6 py-4 font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">
                {{$item->category->name}}
            </td>

            <td scope="row" class="px-6 py-4 font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">
                {{$item->excerpt}}
            </td>

            <td scope="row" class="px-6 py-4 font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">
              <a href="{{route('cms.news.in-house.edit', $item)}}" class="block font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
              <form action="{{route('cms.news.in-house.delete', $item)}}" method="post" class="flex items-center justify-center w-full text-center">
                @csrf
                @method('DELETE')
                <button type="submit" class="block font-medium text-center text-red-600 dark:text-red-500 hover:underline">Remove</button>
              </form>
            </td>
          </tr>
        @empty
          <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            <td scope="row" colspan="4" class="p-3 font-semibold text-center">No Data</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    {{$news->links('components.cms.table.pagination')}}
  </div>
</x-layouts.cms.main>