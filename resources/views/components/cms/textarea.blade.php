@props([
    'label',
    'name',
    'id',
    'placeholder',
    'value',
    'required' => false,
    'error',
])

<div {{$attributes->merge(['class' => "w-full form-control-ff",])}}>
  <label for="{{$id}}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{$label}}</label>
  <textarea
    id="{{$id}}"
    placeholder="{{$placeholder}}"
    rows="4"
    @if ($required) required @endif
    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
  >{{$value}}</textarea>
  @if ($error)
    <p class="mt-2 text-sm text-red-700 dark:text-red-500">{{$error}}</p>  
  @endif
</div>