<?php

namespace App\Enums;

enum CaseStatus: string
{
    case Pending = 'Pending';
    case UnderInvestigation = 'Under Investigation';
    case Filed = 'Filed';
    case Closed = 'Closed';
    case Archived = 'Archived';

    /**
     * Get human-readable label
     */
    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::UnderInvestigation => 'Under Investigation',
            self::Filed => 'Filed',
            self::Closed => 'Closed',
            self::Archived => 'Archived',
        };
    }

    /**
     * Get Tailwind color class
     */
    public function color(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::UnderInvestigation => 'blue',
            self::Filed => 'green',
            self::Closed => 'red',
            self::Archived => 'slate',
        };
    }

    /**
     * Get badge CSS classes
     */
    public function badgeClasses(): string
    {
        return match($this) {
            self::Pending => 'bg-yellow-100 text-yellow-800 ring-yellow-600/20',
            self::UnderInvestigation => 'bg-blue-100 text-blue-800 ring-blue-600/20',
            self::Filed => 'bg-green-100 text-green-800 ring-green-600/20',
            self::Closed => 'bg-red-100 text-red-800 ring-red-600/20',
            self::Archived => 'bg-slate-100 text-slate-800 ring-slate-600/20',
        };
    }

    /**
     * Get icon class
     */
    public function icon(): string
    {
        return match($this) {
            self::Pending => 'fas fa-clock',
            self::UnderInvestigation => 'fas fa-search',
            self::Filed => 'fas fa-check-circle',
            self::Closed => 'fas fa-times-circle',
            self::Archived => 'fas fa-archive',
        };
    }

    /**
     * Check if case is active (not closed)
     */
    public function isActive(): bool
    {
        return !in_array($this, [self::Closed, self::Archived]);
    }

    /**
     * Get all active statuses
     */
    public static function activeStatuses(): array
    {
        return array_filter(self::cases(), fn($status) => $status->isActive());
    }

    /**
     * Get all statuses as options for select dropdown
     */
    public static function options(): array
    {
        return array_map(
            fn($status) => ['value' => $status->value, 'label' => $status->label()],
            self::cases()
        );
    }
}
