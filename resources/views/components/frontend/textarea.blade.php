@props([
    'icon' => null,
    'type' => 'text',
    'name',
    'placeholder',
    'value' => '',
])

<div {{$attributes->merge(['class' => "relative w-full",])}} x-cloak>
  @if (isset($icon))
    <div {{$icon->attributes->merge(['class' => "absolute inset-y-0 left-0 flex items-start pl-3 mt-5 pointer-events-none",])}} >
      {{$icon}}
    </div>
  @endif
  <textarea rows="4" name="{{$name}}" placeholder="{{$placeholder}}" required class="resize-none block w-full p-4 {{isset($icon) ? 'pl-10' : ''}} transition text-sm text-gray-900 border-2 border-gray-300 rounded-2xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{$value}}</textarea>
</div>