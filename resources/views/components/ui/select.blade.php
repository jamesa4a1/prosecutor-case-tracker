{{-- Select Component --}}
@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => '',
    'placeholder' => 'Select an option',
    'required' => false,
    'disabled' => false,
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
    
    <select 
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge([
            'class' => 'w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors disabled:bg-slate-50 disabled:text-slate-500' . ($errors->has($name) ? ' border-red-500 focus:border-red-500 focus:ring-red-500/20' : '')
        ]) }}
    >
        @if($placeholder)
        <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $option)
            @php
                $optionValue = is_array($option) ? ($option['value'] ?? $option['id'] ?? '') : $option;
                $optionLabel = is_array($option) ? ($option['label'] ?? $option['name'] ?? $optionValue) : $option;
            @endphp
            <option value="{{ $optionValue }}" @selected(old($name, $value) == $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
        
        {{ $slot }}
    </select>
    
    @error($name)
    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
