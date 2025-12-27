<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'Admin';
    case Prosecutor = 'Prosecutor';
    case Clerk = 'Clerk';

    public function label(): string
    {
        return $this->value;
    }

    public function description(): string
    {
        return match($this) {
            self::Admin => 'Full system access and user management',
            self::Prosecutor => 'Case management and hearing tracking',
            self::Clerk => 'Data entry and case support',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::Admin => 'bg-purple-100 text-purple-800',
            self::Prosecutor => 'bg-blue-100 text-blue-800',
            self::Clerk => 'bg-green-100 text-green-800',
        };
    }

    public function permissions(): array
    {
        return match($this) {
            self::Admin => [
                'users.view', 'users.create', 'users.edit', 'users.delete',
                'cases.view', 'cases.create', 'cases.edit', 'cases.delete',
                'hearings.view', 'hearings.create', 'hearings.edit', 'hearings.delete',
                'reports.view', 'reports.export',
                'settings.manage', 'audit.view',
            ],
            self::Prosecutor => [
                'cases.view', 'cases.create', 'cases.edit',
                'hearings.view', 'hearings.create', 'hearings.edit',
                'reports.view',
            ],
            self::Clerk => [
                'cases.view', 'cases.create', 'cases.edit',
                'hearings.view', 'hearings.create', 'hearings.edit',
                'reports.view',
            ],
        };
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions());
    }

    public static function options(): array
    {
        return array_map(
            fn($role) => ['value' => $role->value, 'label' => $role->label()],
            self::cases()
        );
    }
}
