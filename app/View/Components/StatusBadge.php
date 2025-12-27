<?php

namespace App\View\Components;

use App\Enums\CaseStatus;
use App\Enums\HearingStatus;
use App\Enums\HearingType;
use App\Enums\PartyType;
use App\Enums\CasePriority;
use App\Enums\UserRole;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class StatusBadge extends Component
{
    public string $classes;
    public string $label;
    public ?string $icon;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public mixed $status,
        public string $size = 'md',
        public bool $showIcon = false
    ) {
        $this->resolveStatus();
    }

    /**
     * Resolve the status to get label, classes, and icon.
     */
    protected function resolveStatus(): void
    {
        if ($this->status instanceof CaseStatus) {
            $this->label = $this->status->label();
            $this->classes = $this->status->badgeClasses();
            $this->icon = $this->showIcon ? $this->status->icon() : null;
        } elseif ($this->status instanceof HearingStatus) {
            $this->label = $this->status->label();
            $this->classes = $this->status->badgeClasses();
            $this->icon = $this->showIcon ? $this->status->icon() : null;
        } elseif ($this->status instanceof HearingType) {
            $this->label = $this->status->shortLabel();
            $this->classes = $this->status->badgeClasses();
            $this->icon = null;
        } elseif ($this->status instanceof PartyType) {
            $this->label = $this->status->label();
            $this->classes = $this->status->badgeClasses();
            $this->icon = $this->showIcon ? $this->status->icon() : null;
        } elseif ($this->status instanceof CasePriority) {
            $this->label = $this->status->label();
            $this->classes = $this->status->badgeClasses();
            $this->icon = $this->showIcon ? $this->status->icon() : null;
        } elseif ($this->status instanceof UserRole) {
            $this->label = $this->status->label();
            $this->classes = $this->status->badgeClasses();
            $this->icon = null;
        } else {
            // Fallback for string status
            $this->label = ucfirst(str_replace('_', ' ', (string) $this->status));
            $this->classes = 'bg-slate-100 text-slate-800';
            $this->icon = null;
        }
    }

    /**
     * Get the size classes.
     */
    public function sizeClasses(): string
    {
        return match($this->size) {
            'sm' => 'px-2 py-0.5 text-xs',
            'lg' => 'px-3 py-1.5 text-sm',
            default => 'px-2.5 py-0.5 text-xs',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.ui.status-badge');
    }
}
