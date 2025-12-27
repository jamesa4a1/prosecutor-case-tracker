<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class EmptyState extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title = 'No data found',
        public ?string $description = null,
        public string $icon = 'fas fa-folder-open',
        public ?string $action = null,
        public ?string $actionUrl = null,
        public string $actionIcon = 'fas fa-plus'
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.ui.empty-state');
    }
}
