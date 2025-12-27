<?php

namespace App\Enums;

enum PartyType: string
{
    case Complainant = 'complainant';
    case Respondent = 'respondent';
    case Witness = 'witness';
    case Victim = 'victim';

    public function label(): string
    {
        return match($this) {
            self::Complainant => 'Complainant',
            self::Respondent => 'Respondent',
            self::Witness => 'Witness',
            self::Victim => 'Victim',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::Complainant => 'bg-blue-100 text-blue-800',
            self::Respondent => 'bg-red-100 text-red-800',
            self::Witness => 'bg-amber-100 text-amber-800',
            self::Victim => 'bg-purple-100 text-purple-800',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Complainant => 'fas fa-user-check',
            self::Respondent => 'fas fa-user-times',
            self::Witness => 'fas fa-user-secret',
            self::Victim => 'fas fa-user-injured',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($type) => ['value' => $type->value, 'label' => $type->label()],
            self::cases()
        );
    }
}
