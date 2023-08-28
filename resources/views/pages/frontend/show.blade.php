<x-layouts.frontend.main title="{{$news->title}}">
  <section>
    <div class="w-full overflow-hidden h-150">
        @if ($news->is_crawl)
          @if ($news->image)
            <img src="{{$news->image}}" alt="" class="object-cover w-full h-full mx-auto">
          @else
            <img src="{{asset('assets/image/no_image_available.png')}}" alt="" class="object-cover w-full h-full mx-auto">
          @endif
        @else
          @if ($news->image)
            <img src="{{asset('storage/' . $news->image)}}" alt="" class="w-full" class="object-cover w-full h-full mx-auto">
          @else
            <img src="{{asset('assets/image/no_image_available.png')}}" alt="" class="object-cover w-full h-full mx-auto">
          @endif
        @endif
    </div>
    <article class="max-w-screen-xl px-5 mx-auto my-5 dark:text-whiten">
      <h1 class="text-2xl font-bold text-justify">{{$news->title}}</h1>
      <small class="block mb-1 text-base font-semibold">
        <a href="#" class="text-meta-1">Source {{$news->source_crawl}}</a>
      </small>
      <div class="flex justify-between gap-5 mt-5 text-lg">
        @if ($news->is_crawl)
          <small class="">
            by <a class="">{{ $news->author_crawl }}</a> in <a class="text-blue-700 hover:text-blue-800 dark:text-blue-600 dark:hover:text-blue-700" href="/?category={{ $news->category->slug ?? null }}">{{ $news->category->name ?? null }}</a>.
          </small>
        @else
          <small class="">
            by <a class="" href="/?author={{ $news->author->username ?? null }}">{{ $news->author->name ?? null }}</a> in <a href="/?category={{ $news->category->slug ?? null }}">{{ $news->category->name ?? null }}</a>.
          </small>
        @endif
        <small class="shrink-0">
          {{ $news->published_at->format('d M Y') }}
        </small>
      </div>
      <div class="my-4 text-justify news-body">
        {!! $news->body !!}
      </div>
    </article>
    <turbo-frame id="comments">
      <div class="max-w-screen-xl px-5 py-10 mx-auto dark:text-whiten">
        @if ($news->comment_status)
          @auth
            @can(App\Policies\CommentPolicy::CREATE, App\Models\Comment::class)
              <form action="{{ route('news.store.comment', $news) }}" method="post" class="w-full px-4 py-2 bg-gray-100 rounded-lg dark:bg-boxdark mb-14">
                @csrf
                <div class="flex flex-wrap mb-6 -mx-3">
                    <h2 class="px-4 pt-3 pb-2 text-lg">Berikan Komentar</h2>
                    <div class="w-full px-3 mt-2 mb-2 md:w-full">
                      <textarea class="w-full h-20 px-3 py-1 font-medium leading-normal border border-gray-400 rounded resize-none dark:bg-boxdark focus:outline-none" name="body" placeholder='Komentar anda' required>{{old('body')}}</textarea>
                      @error('body')
                        <small class="text-red-500">
                          {{$message}}
                        </small>
                      @enderror
                      @error('500')
                        <small class="text-red-500">
                          {{$message}}
                        </small>
                      @enderror
                    </div>
                    <div class="flex items-start justify-end w-full px-3 md:w-full">
                      <div class="-mr-1">
                          <input type='submit' class="px-4 py-1 mr-1 font-medium transition duration-300 border border-gray-400 rounded-lg cursor-pointer tracking-widebg-white hover:bg-gray-100 dark:hover:bg-body" value='Kirim'>
                      </div>
                    </div>
                </div>
              </form>
            @endcan
            @cannot(App\Policies\CommentPolicy::CREATE, App\Models\Comment::class)
              <div class="flex items-center justify-center w-full px-4 py-4 text-lg text-center bg-gray-100 rounded-lg lg:text-base dark:bg-boxdark mb-14">
                <span>Komentar akun kamu sedang ditangguhkan, hubungi <a href="{{route('contact')}}" data-turbo-frame="_top" class="dark:text-blue-500 text-primary">Trusted News</a> untuk info lebih lanjut</span>
              </div>
            @endcannot
          @endauth
          @guest
              <div class="flex items-center justify-center w-full px-4 py-4 text-lg text-center bg-gray-100 rounded-lg lg:text-base dark:bg-boxdark mb-14">
                <span>Kamu harus masuk untuk mengirim komentar silahkan <br/><a data-turbo-frame="_top" href="/login?in={{ urlencode(Request::path()) }}" class="text-meta-5">Login</a>&nbsp;atau&nbsp;<a data-turbo-frame="_top" href="/register?in={{ urlencode(Request::path()) }}" class="text-meta-1">Buat Akun</a>&nbsp;Sekarang!</span>
              </div>
          @endguest
          @foreach ($comments as $index => $comment)
              <div
                x-data="{
                    editComment:false,
                    focus: function() {
                        const textInput = this.$refs.textInput;
                        textInput.focus();
                    }
                }"
                @click.away="editComment = false"
                @keyup.esc="editComment = false"
                id="{{$index}}"
                class="relative grid grid-cols-1 gap-2 p-4 mb-8 transition duration-300 bg-white border rounded-lg dark:border-transparent hover:shadow-lg hover:border-gray-400 dark:bg-boxdark"
              >
                {{-- blur if permission comment revoked --}}
                @if (!$comment->author()->hasPermissionTo('comment'))
                  <div class="absolute inset-0 z-10 flex items-center justify-center font-semibold uppercase rounded-lg bg-white/25 backdrop-blur-md text-danger">
                    Komentar ditangguhkan
                  </div>
                @endif
                <div class="relative flex gap-4">
                  <div class="relative w-20 h-20 -mb-4 overflow-hidden bg-white border rounded-full -top-8">
                    {{-- blur if permission comment revoked --}}
                    @if (!$comment->author()->hasPermissionTo('comment'))
                      <div class="absolute inset-0 z-10 flex items-center justify-center font-semibold uppercase bg-white/25 backdrop-blur-md">
                      </div>
                    @endif
                    <img src="{{$comment->authorRelation->imageImage}}" class="w-full h-full" alt="photo profile {{$comment->authorRelation->name}}" loading="lazy">
                  </div>
                  <div class="flex flex-col w-full">
                    <div class="flex flex-row justify-between">
                      <p class="relative overflow-hidden text-xl truncate whitespace-nowrap">{{$comment->authorRelation->name}}</p>
                      <a class="text-xl" href="#"><i class="fa-solid fa-trash"></i></a>
                    </div>
                    <p class="text-sm text-gray-400">{{ $comment->date }}</p>
                  </div>
                  {{-- action comment --}}
                  @canany([App\Policies\CommentPolicy::UPDATE, App\Policies\CommentPolicy::DELETE], $comment)
                    <div x-show="!editComment" class="absolute top-0 right-0">
                      @can(App\Policies\CommentPolicy::UPDATE, $comment)
                        <button @click="editComment=true; $nextTick(() => focus())" class="p-1 transition duration-300 bg-blue-400 rounded hover:bg-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700">
                          <x-icons.edit />
                        </button>
                      @endcan
                      @can(App\Policies\CommentPolicy::DELETE, $comment)
                        <div x-data="{ modelOpen: false }" class="inline-block">
                          <button
                            @click="modelOpen =!modelOpen"
                            class="p-1 transition duration-300 bg-red-400 rounded hover:bg-red-500 dark:bg-red-600 dark:hover:bg-red-700"
                          >
                            <x-icons.delete />
                          </button>
                          {{-- modal delete --}}
                          <div x-show="modelOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-delete" role="dialog" aria-modal="true">
                            <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                                <div x-cloak @click="modelOpen = false" x-show="modelOpen" 
                                    x-transition:enter="transition ease-out duration-300 transform"
                                    x-transition:enter-start="opacity-0" 
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-200 transform"
                                    x-transition:leave-start="opacity-100" 
                                    x-transition:leave-end="opacity-0"
                                    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-40" aria-hidden="true"
                                ></div>
                                <div x-cloak x-show="modelOpen" 
                                    x-transition:enter="transition ease-out duration-300 transform"
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="transition ease-in duration-200 transform"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    class="inline-block max-w-xl p-4 text-left transition-all transform bg-white rounded-lg shadow mb-59 md:mt-59 md:mb-0 h-max dark:bg-gray-700 w-max 2xl:max-w-2xl"
                                >
                                    <div class="flex items-center justify-end space-x-4">
                                        <button @click="modelOpen = false" class="text-gray-600 transition duration-300 focus:outline-none hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="p-3">
                                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                          Hapus komentar "{{$comment->body}}"
                                        </p>
                                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                          Apakah anda yakin menghapus komentar ini?
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-end p-3 space-x-2 rounded-b">
                                      <button @click="modelOpen = false" type="button" class="px-3 py-1 text-sm font-medium text-gray-500 transition duration-300 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 focus:outline-none">Batal</button>
                                      <form data-turbo-frame="_top" action="{{route('news.delete.comment', ['news' => $news, 'comment' => $comment])}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="px-3 py-1 text-sm font-medium text-center text-white transition duration-300 bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 focus:outline-none">Hapus</button>
                                      </form>
                                    </div>
                                </div>
                            </div>
                          </div>
                        </div>
                      @endcan
                    </div>
                  @endcanany
                </div>
                <div x-show="!editComment" class="-mt-4">
                  @if ($comment->author()->hasPermissionTo('comment'))
                    <p  class="whitespace-pre-line">{{$comment->body}}</p>
                  @else
                    <p  class="whitespace-pre-line text-danger">Komentar Ditangguhkan</p>
                  @endif
                  @if (!$comment->created_at->equalTo($comment->updated_at))
                    <small class="inline-block px-2 py-1 mt-3 text-xs text-white rounded-full bg-meta-4">Edited pada {{$comment->updated_at->diffForHumans()}}</small>  
                  @endif
                </div>
                <template x-if="editComment">
                  <form x-cloak action="{{ route('news.edit.comment', ['news' => $news, 'comment' => $comment]) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="flex flex-wrap">
                        <div class="w-full mt-2 mb-2 md:w-full">
                          <textarea x-ref="textInput" class="w-full px-3 py-1 font-medium leading-normal border border-gray-400 rounded resize-none dark:bg-boxdark focus:outline-none" name="body" placeholder='Type Your Comment' required>{{$comment->body}}</textarea>
                          @error('body')
                            <small class="text-red-500">
                              {{$message}}
                            </small>
                          @enderror
                          @error('500')
                            <small class="text-red-500">
                              {{$message}}
                            </small>
                          @enderror
                        </div>
                        <div class="flex items-center justify-end w-full gap-2 px-3 md:w-full">
                          <div class="">
                              <button @click.prevent="editComment=false" class="text-red-500 dark:text-red-600">Batal</button>
                          </div>
                          <div class="-mr-1">
                              <input type='submit' class="px-4 py-1 mr-1 font-medium transition duration-300 border border-gray-400 rounded-lg cursor-pointer tracking-widebg-white hover:bg-gray-100 dark:hover:bg-body" value='Simpan'>
                          </div>
                        </div>
                    </div>
                  </form>
                </template>
              </div>
          @endforeach
        @else
          <div class="flex items-center justify-center mt-4 mb-5 border-gray-200 dark:text-whiten border-y dark:border-gray-700">
            <h5 class="my-5 font-semibold text-danger">Komentar dimatikan</h5>
          </div>
        @endif
      </div>
    </turbo-frame>
  </section>
</x-layouts.frontend.main>