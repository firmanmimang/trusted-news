@props(['scroll' => 'auto',])

<div {{ $attributes->merge(['class' => 'sticky top-0 z-40 px-5 bg-black text-white dark:text-black dark:bg-white w-full overflow-y-hidden s no-scrollbar',]) }}>
  <div class="relative flex gap-5 group">
    <ul class="flex w-full justify-between gap-5 items-center whitespace-nowrap h-13 @if(count($categoriesGlobal) >= 20) animate-marquee @endif group-hover:pause">
      @foreach ($categoriesGlobal as $category)
        <li class="font-semibold {{request()->get('category') === $category->slug ? 'text-red-500' : null}}">
          <a href="{{route('home', ['category' => $category->slug])}}">{{$category->name}}</a>
        </li>
      @endforeach
    </ul>
    @if(count($categoriesGlobal) >= 20)
      <ul class="absolute top-0 flex w-full gap-5 justify-evenly items-center whitespace-nowrap h-13 @if(count($categoriesGlobal) >= 20) animate-marquee2 @endif group-hover:pause ms-5">
        @foreach ($categoriesGlobal as $category)
          <li class="font-semibold {{request()->get('category') === $category->slug ? 'text-red-500' : null}}">
            <a href="{{route('home', ['category' => $category->slug])}}">{{$category->name}}</a>
          </li>  
        @endforeach
      </ul>
    @endif
  </div>
</div>