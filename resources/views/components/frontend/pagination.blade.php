@if ($paginator->hasPages())
  <nav aria-label="Page navigation" class="my-5">
    <ul class="inline-flex -space-x-px">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
        <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
            <span aria-hidden="true" class="px-3 py-2 text-blue-600 border border-gray-300 rounded-l-lg bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">Previous</span>
        </li>
      @else
        <li>
          <a data-turbo-preload href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            Previous
          </a>
        </li>
      @endif
      {{-- Pagination Elements --}}
      @foreach ($elements as $element)
          {{-- "Three Dots" Separator --}}
          @if (is_string($element))
              <li class="disabled" aria-disabled="true">
                <span class="px-3 py-2 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">{{ $element }}</span>
              </li>
          @endif

          {{-- Array Of Links --}}
          @if (is_array($element))
              @foreach ($element as $page => $url)
                  @if ($page == $paginator->currentPage())
                      <li aria-current="page">
                        <span class="px-3 py-2 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">{{ $page }}</span>
                      </li>
                  @else
                      <li>
                        <a href="{{ $url }}" class="px-3 py-2 text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                          {{ $page }}
                        </a>
                      </li>
                  @endif
              @endforeach
          @endif
      @endforeach
      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
          <li>
              <a data-turbo-preload href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                Next
              </a>
          </li>
      @else
          <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
              <span aria-hidden="true" class="px-3 py-2 text-blue-600 border border-gray-300 rounded-r-lg bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">Next</span>
          </li>
      @endif
    </ul>
  </nav>
@endif