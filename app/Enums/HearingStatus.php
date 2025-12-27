<?php

namespace App\Enums;

enum HearingStatus: string
{
    case Scheduled = 'scheduled';
    case Completed = 'completed';
    case Postponed = 'postponed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Scheduled => 'Scheduled',
            self::Completed => 'Completed',
            self::Postponed => 'Postponed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::Scheduled => 'bg-blue-100 text-blue-800',
            self::Completed => 'bg-green-100 text-green-800',
            self::Postponed => 'bg-amber-100 text-amber-800',
            self::Cancelled => 'bg-red-100 text-red-800',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Scheduled => 'fas fa-calendar-check',
            self::Completed => 'fas fa-check-circle',
            self::Postponed => 'fas fa-clock',
            self::Cancelled => 'fas fa-times-circle',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($status) => ['value' => $status->value, 'label' => $status->label()],
            self::cases()
        );
    }
}
