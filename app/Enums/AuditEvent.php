<?php

namespace App\Enums;

enum AuditEvent: string
{
    case Created = 'created';
    case Updated = 'updated';
    case Deleted = 'deleted';
    case Restored = 'restored';
    case Login = 'login';
    case Logout = 'logout';
    case Viewed = 'viewed';
    case Downloaded = 'downloaded';
    case Exported = 'exported';

    public function label(): string
    {
        return match($this) {
            self::Created => 'Created',
            self::Updated => 'Updated',
            self::Deleted => 'Deleted',
            self::Restored => 'Restored',
            self::Login => 'Logged In',
            self::Logout => 'Logged Out',
            self::Viewed => 'Viewed',
            self::Downloaded => 'Downloaded',
            self::Exported => 'Exported',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Created => 'fas fa-plus-circle text-green-600',
            self::Updated => 'fas fa-edit text-blue-600',
            self::Deleted => 'fas fa-trash text-red-600',
            self::Restored => 'fas fa-undo text-purple-600',
            self::Login => 'fas fa-sign-in-alt text-green-600',
            self::Logout => 'fas fa-sign-out-alt text-slate-600',
            self::Viewed => 'fas fa-eye text-blue-600',
            self::Downloaded => 'fas fa-download text-amber-600',
            self::Exported => 'fas fa-file-export text-cyan-600',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::Created => 'bg-green-100 text-green-800',
            self::Updated => 'bg-blue-100 text-blue-800',
            self::Deleted => 'bg-red-100 text-red-800',
            self::Restored => 'bg-purple-100 text-purple-800',
            self::Login => 'bg-green-100 text-green-800',
            self::Logout => 'bg-slate-100 text-slate-800',
            self::Viewed => 'bg-blue-100 text-blue-800',
            self::Downloaded => 'bg-amber-100 text-amber-800',
            self::Exported => 'bg-cyan-100 text-cyan-800',
        };
    }
}
