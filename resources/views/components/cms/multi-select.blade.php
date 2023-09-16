@props([
  'option' => null,
  'label' => '',
  'error' => '',
  'id' => '',
  'name' => '',
  'multiple' => false,
])

<div class="form-control-ff">
  <label for="{{$id}}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{$label}}</label>
  <select @if($multiple) multiple @endif id="{{$id}}" name="{{$name}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    {{$option}}
  </select>
  <p class="mt-2 text-sm text-red-700 dark:text-red-500">{{$error}}</p>
</div>