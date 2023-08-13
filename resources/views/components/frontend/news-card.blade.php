<div class="flex flex-col max-w-sm bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
  <a href="/{{$news->slug}}" class="overflow-hidden rounded-t-lg shrink-0 h-49">
    @if ($news->is_crawl)
      <img src="{{ $news->image }}" alt="{{ $news->image_description ?? '' }}" class="object-cover w-full h-full">
    @else
      <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->category->name }}" class="object-cover w-full h-full">
    @endif
  </a>
  <div class="flex flex-col justify-between p-5 grow">
    <div>
      <a href="/{{$news->slug}}">
          <h2 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white line-clamp-3">{{$news->title}}</h2>
      </a>
      <small class="block mb-1 text-base font-semibold text-gray-900 dark:text-white">
        <a href="?{{request('q')? 'q='.request('q').'&':''}}s={{$news->source_crawl}}" class="text-meta-1">Source {{$news->source_crawl}}</a>
      </small>
      <small class="dark:text-whiten">
        @if ($news->is_crawl)
          <span class="flex justify-between">
            <a class="dark:text-whiten line-clamp-1">by {{ $news->author_crawl }}</a>
            <p class="shrink-0">{{ $news->published_at->format('d M Y') }}</p>
          </span>
        @else
            <span class="flex justify-between">
              <a class="dark:text-whiten line-clamp-1" href="{{ route('search', ['author' => $news->author->username ?? null ]) }}">by {{ $news->author->name ?? '' }}</a>
              <p class="shrink-0">{{ $news->updated_at->format('d M Y') }}</p>
            </span>
        @endif
      </small>
      <p class="mt-3 mb-3 font-normal text-justify text-gray-700 dark:text-gray-400 whitespace-break-spaces">{{$news->excerpt}}</p>
    </div>
    <a href="/{{$news->slug}}" data-preserve-scroll class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white transition duration-200 bg-blue-700 rounded-lg max-w-max hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        Read more
        <svg class="w-4 h-4 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
    </a>
  </div>
</div>