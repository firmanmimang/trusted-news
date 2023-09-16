{{-- @if ($paginator->hasPages()) --}}
  <nav class="flex items-center justify-between pt-4" aria-label="Table navigation">
    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Showing <span class="font-semibold text-gray-900 dark:text-white">{{$paginator->firstItem()}} - {{$paginator->lastItem()}}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{$paginator->total()}}</span></span>
    <div class="flex items-stretch gap-2">
      {{-- dropdown flowbite size table --}}
      <div
        class="h-8"
        x-cloak
        x-data="{
          size:{{request()->get('size')??10}}
        }"
      >
        <button x-text="size" id="sizeActionButton" data-dropdown-toggle="dropdownAction" class="flex items-center h-full text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-0 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button">
          10
        </button>
        <!-- Dropdown menu -->
        <div id="dropdownAction" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownActionButton">
                <li>
                    <a href="{{request()->url()}}?size=5" class="@if(request()->get('size') == 5) bg-gray-100 dark:bg-gray-600 @endif block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">5</a>
                </li>
                <li>
                    <a href="{{request()->url()}}?size=10" class="@if(request()->get('size') == 10) bg-gray-100 dark:bg-gray-600 @endif block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">10</a>
                </li>
                <li>
                    <a href="{{request()->url()}}?size=50" class="@if(request()->get('size') == 50) bg-gray-100 dark:bg-gray-600 @endif block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">50</a>
                </li>
                <li>
                    <a href="{{request()->url()}}?size=100" class="@if(request()->get('size') == 100) bg-gray-100 dark:bg-gray-600 @endif block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">100</a>
                </li>
                <li>
                    <a href="{{request()->url()}}?size=500" class="@if(request()->get('size') == 500) bg-gray-100 dark:bg-gray-600 @endif block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">500</a>
                </li>
            </ul>
        </div>
      </div>
      <ul class="inline-flex -space-x-px text-sm h-8">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
          <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
              <span class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</span>
          </li>
        @else
          <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
            <a data-turbo-preload href="{{ $paginator->previousPageUrl() }}" class="cursor-pointer flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
          </li>
        @endif
        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
          {{-- "Three Dots" Separator --}}
          @if (is_string($element))
            <li class="disabled" aria-disabled="true">
              <span class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                {{ $element }}
              </span>
            </li>
          @endif
  
          {{-- Array Of Links --}}
          @if (is_array($element))
            @foreach ($element as $page => $url)
              @if ($page == $paginator->currentPage())
                <li aria-current="page">
                  <span class="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">
                    {{$page}}
                  </span>
                </li>
              @else
                <li>
                  <a href{{$url}}" class="cursor-pointer  flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    {{$page}}
                  </a>
                </li>
              @endif
            @endforeach
          @endif
        @endforeach
        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
          <li>
            <a data-turbo-preload href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" class="cursor-pointer flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
              Next
            </a>
          </li>
        @else
          <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
            <span aria-hidden="true" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
              Next
            </span>
          </li>
        @endif
      </ul>
    </div>
  </nav>
{{-- @endif --}}