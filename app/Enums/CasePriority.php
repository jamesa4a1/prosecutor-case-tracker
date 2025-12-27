<?php

namespace App\Enums;

enum CasePriority: string
{
    case Low = 'low';
    case Normal = 'normal';
    case High = 'high';
    case Urgent = 'urgent';

    public function label(): string
    {
        return match($this) {
            self::Low => 'Low',
            self::Normal => 'Normal',
            self::High => 'High',
            self::Urgent => 'Urgent',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::Low => 'bg-slate-100 text-slate-700',
            self::Normal => 'bg-blue-100 text-blue-700',
            self::High => 'bg-orange-100 text-orange-700',
            self::Urgent => 'bg-red-100 text-red-700',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Low => 'fas fa-arrow-down',
            self::Normal => 'fas fa-minus',
            self::High => 'fas fa-arrow-up',
            self::Urgent => 'fas fa-exclamation-triangle',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($priority) => ['value' => $priority->value, 'label' => $priority->label()],
            self::cases()
        );
    }
}
