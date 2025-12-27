<?php

namespace App\Enums;

enum DocumentType: string
{
    case Complaint = 'complaint';
    case Affidavit = 'affidavit';
    case Evidence = 'evidence';
    case Resolution = 'resolution';
    case Motion = 'motion';
    case Order = 'order';
    case Subpoena = 'subpoena';
    case Other = 'other';

    public function label(): string
    {
        return match($this) {
            self::Complaint => 'Complaint',
            self::Affidavit => 'Affidavit',
            self::Evidence => 'Evidence',
            self::Resolution => 'Resolution',
            self::Motion => 'Motion',
            self::Order => 'Court Order',
            self::Subpoena => 'Subpoena',
            self::Other => 'Other Document',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Complaint => 'fas fa-file-signature',
            self::Affidavit => 'fas fa-file-contract',
            self::Evidence => 'fas fa-file-image',
            self::Resolution => 'fas fa-file-alt',
            self::Motion => 'fas fa-file-invoice',
            self::Order => 'fas fa-gavel',
            self::Subpoena => 'fas fa-file-export',
            self::Other => 'fas fa-file',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::Complaint => 'bg-red-100 text-red-800',
            self::Affidavit => 'bg-blue-100 text-blue-800',
            self::Evidence => 'bg-amber-100 text-amber-800',
            self::Resolution => 'bg-green-100 text-green-800',
            self::Motion => 'bg-purple-100 text-purple-800',
            self::Order => 'bg-indigo-100 text-indigo-800',
            self::Subpoena => 'bg-cyan-100 text-cyan-800',
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
