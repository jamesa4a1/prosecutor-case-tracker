<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CaseModel extends Model
{
    use HasFactory;

    protected $table = 'cases';

    protected $fillable = [
        'case_number',
        'title',
        'offense',
        'type',
        'date_filed',
        'status',
        'complainant',
        'accused',
        'investigating_officer',
        'agency_station',
        'prosecutor_id',
        'next_hearing_at',
        'court_branch',
        'notes',
    ];

    protected $casts = [
        'date_filed' => 'date',
        'next_hearing_at' => 'datetime',
    ];

    const STATUSES = [
        'For Filing',
        'Under Preliminary Investigation',
        'Filed in Court',
        'For Archive',
        'Closed',
    ];

    const TYPES = [
        'Criminal',
        'Civil',
        'Special',
    ];

    public function prosecutor()
    {
        return $this->belongsTo(Prosecutor::class);
    }

    public function hearings()
    {
        return $this->hasMany(Hearing::class, 'case_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'case_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(StatusHistory::class, 'case_id');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'For Filing' => 'bg-yellow-100 text-yellow-800',
            'Under Preliminary Investigation' => 'bg-blue-100 text-blue-800',
            'Filed in Court' => 'bg-purple-100 text-purple-800',
            'For Archive' => 'bg-gray-100 text-gray-800',
            'Closed' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
