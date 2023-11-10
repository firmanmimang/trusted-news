<x-layouts.cms.main title="Classification News">
  <form action="{{route('cms.classification.process')}}" method="POST" data-turbo="false">
    @csrf
    <x-cms.textfield name="title" id="title" placeholder="masukan judul berita . . ."/>
    <button class="mt-2 py-1 px-3 bg-blue-500 text-white rounded-lg text-sm">Submit</button>
  </form>
  @foreach ($words as $item)
      <p>{{$item}}</p>
  @endforeach
</x-layouts.cms.main>