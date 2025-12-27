{{-- Alert Component --}}
<div 
    x-data="{ show: true }" 
    x-show="show"
    x-cloak
    {{ $attributes->merge(['class' => "rounded-lg border px-4 py-3 {$colorClasses}"]) }}
    role="alert"
>
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="{{ $iconClass }}"></i>
        </div>
        <div class="ml-3 flex-1">
            @if($title)
            <h4 class="font-medium">{{ $title }}</h4>
            @endif
            <div class="text-sm {{ $title ? 'mt-1' : '' }}">
                {{ $slot }}
            </div>
        </div>
        @if($dismissible)
        <div class="ml-4 flex-shrink-0">
            <button 
                @click="show = false" 
                type="button" 
                class="inline-flex rounded-md p-1.5 hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors"
            >
                <span class="sr-only">Dismiss</span>
                <i class="fas fa-times text-current opacity-70"></i>
            </button>
        </div>
        @endif
    </div>
</div>
