<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prosecutor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'position',
        'email',
        'office',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cases()
    {
        return $this->hasMany(CaseModel::class, 'prosecutor_id');
    }

    public function assignedHearings()
    {
        return $this->hasMany(Hearing::class, 'assigned_prosecutor_id');
    }

    public function activeCasesCount(): int
    {
        return $this->cases()->whereNotIn('status', ['Closed', 'Archived'])->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
