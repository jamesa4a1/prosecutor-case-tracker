{{-- Card Component --}}
<div {{ $attributes->merge([
    'class' => 'bg-white rounded-xl border border-slate-200 shadow-sm ' . 
               ($hover ? 'transition-shadow hover:shadow-md' : '')
]) }}>
    @if($title || isset($actions))
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 {{ $headerClass }}">
        <div>
            @if($title)
            <h3 class="text-base font-semibold text-slate-900">{{ $title }}</h3>
            @endif
            @if($subtitle)
            <p class="text-sm text-slate-500 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        @if(isset($actions))
        <div class="flex items-center gap-2">{{ $actions }}</div>
        @endif
    </div>
    @endif
    
    <div @class(['px-6 py-4' => $padding])>
        {{ $slot }}
    </div>
    
    @if(isset($footer))
    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 rounded-b-xl">
        {{ $footer }}
    </div>
    @endif
</div>
