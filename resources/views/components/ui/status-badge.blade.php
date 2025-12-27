{{-- Status Badge Component --}}
<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full font-medium ring-1 ring-inset {$classes} {$sizeClasses}"]) }}>
    @if($icon)
        <i class="{{ $icon }} mr-1"></i>
    @endif
    {{ $label }}
</span>
