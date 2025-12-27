{{-- Modal Component --}}
<div
    x-data="{ open: false }"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') open = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') open = false"
    x-on:keydown.escape.window="open = false"
    x-cloak
    {{ $attributes }}
>
    {{-- Modal Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-black/50"
        @if($closeable) @click="open = false" @endif
    ></div>

    {{-- Modal Content --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full {{ $maxWidthClass() }}">
                {{-- Modal Header --}}
                @if($title || $closeable)
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                    @if($title)
                    <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
                    @else
                    <div></div>
                    @endif
                    @if($closeable)
                    <button @click="open = false" type="button" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                    @endif
                </div>
                @endif

                {{-- Modal Body --}}
                <div class="px-6 py-4">
                    {{ $slot }}
                </div>

                {{-- Modal Footer --}}
                @if(isset($footer))
                <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">
                    {{ $footer }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
