<?php

namespace App\Enums;

enum HearingType: string
{
    case Preliminary = 'preliminary';
    case Arraignment = 'arraignment';
    case PreTrial = 'pre_trial';
    case Trial = 'trial';
    case Promulgation = 'promulgation';
    case Motion = 'motion';
    case Other = 'other';

    public function label(): string
    {
        return match($this) {
            self::Preliminary => 'Preliminary Investigation',
            self::Arraignment => 'Arraignment',
            self::PreTrial => 'Pre-Trial Conference',
            self::Trial => 'Trial',
            self::Promulgation => 'Promulgation',
            self::Motion => 'Motion Hearing',
            self::Other => 'Other',
        };
    }

    public function shortLabel(): string
    {
        return match($this) {
            self::Preliminary => 'Preliminary',
            self::Arraignment => 'Arraignment',
            self::PreTrial => 'Pre-Trial',
            self::Trial => 'Trial',
            self::Promulgation => 'Promulgation',
            self::Motion => 'Motion',
            self::Other => 'Other',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::Preliminary => 'bg-blue-100 text-blue-800',
            self::Arraignment => 'bg-purple-100 text-purple-800',
            self::PreTrial => 'bg-amber-100 text-amber-800',
            self::Trial => 'bg-red-100 text-red-800',
            self::Promulgation => 'bg-green-100 text-green-800',
            self::Motion => 'bg-cyan-100 text-cyan-800',
            self::Other => 'bg-slate-100 text-slate-800',
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
