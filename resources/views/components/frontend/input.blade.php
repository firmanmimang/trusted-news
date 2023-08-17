@props([
    'icon' => null,
    'toggle' => false,
    'type' => 'text',
    'name',
    'placeholder',
    'value' => '',
])

<div @if($toggle) x-cloak x-data="{toggleInput: false, inputValue: '',}" @endif {{$attributes->merge(['class' => "relative w-full",])}}>
  @if ($icon)
    <div {{$icon->attributes->merge(['class' => "absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none",])}} >
      {{$icon}}
    </div>
  @endif
  @if ($toggle)
    <div @click.prevent="toggleInput = !toggleInput" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer">
      <x-icons.eye x-show="!toggleInput" class="fill-gray-400" width="20" height="20"/>
      <x-icons.eye-slash x-show="toggleInput" class="fill-gray-400" width="20" height="20"/>
    </div>
  @endif
  <input @if($toggle) x-show="!toggleInput" x-model="inputValue" @endif value="{{$value}}" type="{{$type}}" name="{{$name}}" placeholder="{{$placeholder}}" required class="block w-full p-4 pl-10 text-sm text-gray-900 border-2 border-gray-300 rounded-2xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
  @if($toggle)
    <input @if($toggle) x-show="toggleInput" x-model="inputValue" @endif value="{{$value}}" type="text" name="{{$name}}" placeholder="{{$placeholder}}" required class="block w-full p-4 pl-10 text-sm text-gray-900 border-2 border-gray-300 rounded-2xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">  
  @endif
</div>