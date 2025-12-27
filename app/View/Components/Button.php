<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Button extends Component
{
    public string $variantClasses;
    public string $sizeClasses;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $variant = 'primary',
        public string $size = 'md',
        public ?string $icon = null,
        public string $iconPosition = 'left',
        public bool $loading = false,
        public string $type = 'button',
        public ?string $href = null
    ) {
        $this->variantClasses = $this->getVariantClasses();
        $this->sizeClasses = $this->getSizeClasses();
    }

    /**
     * Get variant CSS classes.
     */
    protected function getVariantClasses(): string
    {
        return match($this->variant) {
            'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm',
            'secondary' => 'bg-white text-slate-700 hover:bg-slate-50 border border-slate-300 focus:ring-blue-500',
            'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 shadow-sm',
            'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 shadow-sm',
            'warning' => 'bg-amber-500 text-white hover:bg-amber-600 focus:ring-amber-500 shadow-sm',
            'ghost' => 'text-slate-600 hover:bg-slate-100 hover:text-slate-900',
            'link' => 'text-blue-600 hover:text-blue-800 underline-offset-2 hover:underline',
            default => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm',
        };
    }

    /**
     * Get size CSS classes.
     */
    protected function getSizeClasses(): string
    {
        return match($this->size) {
            'xs' => 'px-2 py-1 text-xs',
            'sm' => 'px-3 py-1.5 text-sm',
            'lg' => 'px-6 py-3 text-base',
            'xl' => 'px-8 py-4 text-lg',
            default => 'px-4 py-2 text-sm',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.ui.button');
    }
}
