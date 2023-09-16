@props([
  'icon' => null,
  'label' => '',
  'error' => '',
  'type' => 'text',
  'name' => '',
  'placeholder' => '',
  'value' => '',
  'id' => '',
  'disabled' => false,
  'required' => false,
  'toggle' => false,
])

<div @if($toggle) x-cloak x-data="{toggleInput: false, inputValue: '{{$value}}',}" @endif class="form-control-ff">
  <label for="{{$id}}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{$label}}</label>
  <div class="relative">
    @if ($icon)
      <div {{$icon->attributes->merge(['class'=>"absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none"])}}>
        {{$icon}}
      </div>
    @endif
    <input @if($toggle) x-show="!toggleInput" x-model="inputValue" @endif @if($required) required @endif @if($disabled) readonly disabled @endif value="{{$value}}" type="{{$type}}" name="{{$name}}" placeholder="{{$placeholder}}" id="{{$id}}" class="@if($icon) pl-10 @endif @if($disabled) cursor-not-allowed @endif bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    @if($toggle)
      <input @if($toggle) x-show="toggleInput" x-model="inputValue" @endif @if($required) required @endif @if($disabled) readonly disabled @endif value="{{$value}}" type="text" name="{{$name}}" placeholder="{{$placeholder}}" id="{{$id}}" class="@if($icon) pl-10 @endif @if($disabled) cursor-not-allowed @endif bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    @endif
    @if ($toggle)
      <div tabindex="0" @keyup.enter="toggleInput = !toggleInput" @click.prevent="toggleInput = !toggleInput" class="absolute inset-y-0 right-0 flex items-center justify-center pr-3 cursor-pointer focus:outline-none focus:border-0 group">
        <x-icons.eye x-show="!toggleInput" class="transition duration-300 fill-gray-400 group-focus:fill-primary" width="20" height="20"/>
        <x-icons.eye-slash x-show="toggleInput" class="transition duration-300 fill-gray-400 group-focus:fill-primary" width="20" height="20"/>
      </div>
    @endif
  </div>
  <p class="mt-2 text-sm text-red-700 dark:text-red-500">{{$error}}</p>
</div>