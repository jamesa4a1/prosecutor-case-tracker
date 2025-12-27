<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Alert extends Component
{
    public string $colorClasses;
    public string $iconClass;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $type = 'info',
        public ?string $title = null,
        public bool $dismissible = false
    ) {
        $this->colorClasses = $this->getColorClasses();
        $this->iconClass = $this->getIconClass();
    }

    /**
     * Get color CSS classes based on type.
     */
    protected function getColorClasses(): string
    {
        return match($this->type) {
            'success' => 'bg-green-50 border-green-200 text-green-800',
            'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
            'error', 'danger' => 'bg-red-50 border-red-200 text-red-800',
            default => 'bg-blue-50 border-blue-200 text-blue-800',
        };
    }

    /**
     * Get icon class based on type.
     */
    protected function getIconClass(): string
    {
        return match($this->type) {
            'success' => 'fas fa-check-circle text-green-500',
            'warning' => 'fas fa-exclamation-triangle text-amber-500',
            'error', 'danger' => 'fas fa-exclamation-circle text-red-500',
            default => 'fas fa-info-circle text-blue-500',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.ui.alert');
    }
}
