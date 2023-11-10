@props(['id', 'value', 'label' => '' ,'name', 'disabled' => false, 'error'=>''])
 
<div class="form-control-ff">
  <label for="{{$id}}">{{$label}}</label>
  <input type="hidden" id="{{ $id }}_input" name="{{ $name }}" value="{!! $value !!}" />
  <trix-editor id="{{ $id }}" input="{{ $id }}_input" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'p-2 trix-content rounded-md shadow-sm border bg-gray-50 dark:bg-gray-700 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) !!}></trix-editor>
  @if($error)
    <p class="mt-2 text-sm text-red-700 dark:text-red-500">{{$error}}</p>
  @endif
</div>

@push('javascriptNduwur')
  <x-rich-text-trix-styles />
@endpush