@props([
    'icon',
    'type' => 'text',
    'name',
    'placeholder',
    'value',
])

<div {{$attributes->merge(['class' => "relative w-full",])}}>
  <div {{$icon->attributes->merge(['class' => "absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none",])}} >
     {{$icon}}
  </div>
  <input value="{{$value}}" type="{{$type}}" name="{{$name}}" placeholder="{{$placeholder}}" required class="block w-full p-4 pl-10 text-sm text-gray-900 border-2 border-gray-300 rounded-2xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
</div>