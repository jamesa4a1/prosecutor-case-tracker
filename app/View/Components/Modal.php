<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Modal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name,
        public ?string $title = null,
        public string $maxWidth = 'lg',
        public bool $closeable = true
    ) {}

    /**
     * Get max width classes.
     */
    public function maxWidthClass(): string
    {
        return match($this->maxWidth) {
            'sm' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'xl' => 'sm:max-w-xl',
            '2xl' => 'sm:max-w-2xl',
            '3xl' => 'sm:max-w-3xl',
            '4xl' => 'sm:max-w-4xl',
            '5xl' => 'sm:max-w-5xl',
            'full' => 'sm:max-w-full',
            default => 'sm:max-w-lg',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.ui.modal');
    }
}
