<x-layouts.cms.main title="Edit News">
  <x-cms.title title="Edit News" subtitle="Edit News"/>
  <form novalidate action="{{route('cms.news.crawl.update', $news)}}" method="POST" enctype="multipart/form-data" >
    @csrf
    @method('PUT')
    <label for="photo-news" class="block m-auto cursor-pointer w-96"
      x-data="{
        form: {
          image: '{{$news->image}}',
        },
        handleInputImage(e){
          if(e.target.files[0]){
            this.form.image = URL.createObjectURL(e.target.files[0])
          }else{
            this.form.image = '{{$news->image}}'
          }
        },
      }"
    >
      <div class="m-auto overflow-hidden aspect-w-16 aspect-h-9 form-control-ff">
        <img :src="form.image" x-cloak alt="photo news" class="object-cover w-full h-full">
      </div>
      <input @input="handleInputImage" type="file" name="image" id="photo-news" hidden>
      @if($errors->first('image')) <p class="mt-2 text-sm text-center text-red-700 dark:text-red-500">{{$errors->first('image')}}</p> @endif
    </label>
    <x-cms.textfield error="{{$errors->first('image_description')}}" value="{{old('image_description', $news->image_description)}}" type="text" name="image_description" :required="true" id="image_description" label="Image Description" placeholder="Image Description" />
    <x-cms.textfield error="{{$errors->first('title')}}" value="{{old('title', $news->title)}}" type="text" name="title" :required="true" id="title" label="Title" placeholder="Title" />
    <x-cms.textarea
      error="{{$errors->first('excerpt')}}"
      value="{{old('excerpt', $news->excerpt)}}"
      name="excerpt"
      :required="true"
      id="excerpt"
      label="Excerpt"
      placeholder="Excerpt (optional)" />

    {{-- <x-cms.ckeditor5
      error="{{$errors->first('description')}}"
      value="{{old('description')}}"
      name="description"
      :required="true"
      id="description"
      label="Description" /> --}}

    <x-cms.trix-editor
      name="description"
      id="description"
      value="{{old('description', $news->body->render())}}"
      error="{{$errors->first('description')}}"
    />

    <x-cms.multi-select error="{{$errors->first('category')}}" id="category" name="category" label="Category">
      <x-slot:option>
        <option value="">Choose Category</option>
        @foreach ( $categories as $category)
          <option @if ($category->id == old('category', $news->category->id)) selected @endif value="{{$category->id}}">{{$category->name}}</option>
        @endforeach
      </x-slot:option>
    </x-cms.multi-select>

    <div class="grid grid-cols-2 gap-2 form-control-ff">
      <div>
        <div class="flex items-center pl-4 border border-gray-200 rounded dark:border-gray-700">
          <input @if (old('publish_status', $news->publish_status) == 'true') checked @endif id="publish_status" type="checkbox" value="true" name="publish_status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="publish_status" class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Publish Status</label>
        </div>
        @if($errors->first('publish_status'))
          <p class="mt-2 text-sm text-red-700 dark:text-red-500">{{$errors->first('publish_status')}}</p>
        @endif
      </div>
      <div>
        <div class="flex items-center pl-4 border border-gray-200 rounded dark:border-gray-700">
          <input @if (old('comment_status', $news->comment_status) == 'true') checked @endif id="comment_status" type="checkbox" value="true" name="comment_status" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="comment_status" class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Comment Status</label>
        </div>
        @if($errors->first('comment_status'))
          <p class="mt-2 text-sm text-red-700 dark:text-red-500">{{$errors->first('comment_status')}}</p>
        @endif
      </div>
    </div>

    <button type="submit" class="text-white form-control-ff bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
  </form>
</x-layouts.cms.main>