<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hearing extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'date_time',
        'court_branch',
        'assigned_prosecutor_id',
        'result_status',
        'remarks',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function case()
    {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }

    public function assignedProsecutor()
    {
        return $this->belongsTo(Prosecutor::class, 'assigned_prosecutor_id');
    }
}
