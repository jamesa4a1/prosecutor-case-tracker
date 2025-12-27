{{-- Textarea Component --}}
@props([
    'name',
    'label' => null,
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'disabled' => false,
    'rows' => 4,
    'hint' => null,
])

<div>
    @if($label)
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-2">
        {{ $label }}
        @if($required)
        <span class="text-red-500">*</span>
        @endif
    </label>
    @endif
    
    <textarea 
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge([
            'class' => 'w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors disabled:bg-slate-50 disabled:text-slate-500 resize-y' . ($errors->has($name) ? ' border-red-500 focus:border-red-500 focus:ring-red-500/20' : '')
        ]) }}
    >{{ old($name, $value) }}</textarea>
    
    @error($name)
    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
    @enderror
    
    @if($hint && !$errors->has($name))
    <p class="mt-1.5 text-sm text-slate-500">{{ $hint }}</p>
    @endif
</div>
