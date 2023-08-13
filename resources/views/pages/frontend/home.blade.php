<x-layouts.frontend.main title="Berita Terbaru">
    <section class="px-5 mt-8">
      <turbo-frame data-turbo-action="advance" target="_top">
        <div class="absolute invisible" id="berita"></div>
        @if (request('q'))
          <h1 class="text-2xl font-bold -mt-14 pt-14">Hasil Pencarian Berita "{{request('q')}}"</h1>
        @else
          <h1 class="text-2xl font-bold -mt-14 pt-14">Berita Terbaru</h1>
          <p>Kumpulan berita-berita terbaru dari berbagai sumber terpercaya</p>
        @endif
        <div class="grid grid-cols-4 gap-3 mt-8">
          {{-- card --}}
          @forelse ($news as $item)
            <x-frontend.news-card :news="$item" />
          @empty
            <p class="col-span-4 text-xl font-semibold text-center text-danger">Berita belum tersedia</p>
          @endforelse
        </div>
        <div class="flex items-center justify-center py-8">
          {{$news->links('components.frontend.pagination')}}
        </div>
      </turbo-frame>
    </section>
</x-layouts.frontend.main>