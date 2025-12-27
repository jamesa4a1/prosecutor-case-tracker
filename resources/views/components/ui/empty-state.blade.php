{{-- Empty State Component --}}
<div {{ $attributes->merge(['class' => 'text-center py-12']) }}>
    <div class="w-16 h-16 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4">
        <i class="{{ $icon }} text-2xl text-slate-400"></i>
    </div>
    <h3 class="text-base font-medium text-slate-900 mb-1">{{ $title }}</h3>
    @if($description)
    <p class="text-sm text-slate-500 mb-4 max-w-sm mx-auto">{{ $description }}</p>
    @endif
    
    {{ $slot }}
    
    @if($action && $actionUrl)
    <a href="{{ $actionUrl }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors mt-4">
        <i class="{{ $actionIcon }} mr-2"></i>
        {{ $action }}
    </a>
    @endif
</div>
