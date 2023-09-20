@props([
    'label',
    'name',
    'id',
    'placeholder',
    'value',
    'error',
])

@push('javascriptNduwur')
  <script src="/assets/vendor/ckeditor5/build/ckeditor.js" data-turbo-eval="false"></script>
@endpush

<div {{$attributes->merge(['class' => "w-full form-control-ff",])}}>
  <label for="{{$id}}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{$label}}</label>
  <div id="{{$id}}"></div>
  <input type="hidden" name="{{$name}}" value="{!!$value!!}" id="{{$id}}-input"> <!-- Note the added 'id' attribute -->
  @if ($error)
    <p class="mt-2 text-sm text-red-700 dark:text-red-500">{{$error}}</p>  
  @endif

</div>
<script defer data-turbo-eval="false">
  ClassicEditor
    .create(document.querySelector('#{{$id}}'), {})
    .then(editor => {
        // Listen for the 'change' event in CKEditor
        editor.model.document.on('change:data', () => {
            // Update the hidden input field's value with the CKEditor content
            const inputField = document.querySelector('#{{$id}}-input');
            if (inputField) {
                inputField.value = editor.getData();
            }
        });
    })
    .catch(error => {
        console.error(error);
    });

  
  // Remove the CKEditor instance when navigating away using Turbo Drive
  // document.addEventListener('turbo:before-visit', function () {
  //     const elementCKEditor = document.querySelector('#{{$id}}');

  // });
</script>
