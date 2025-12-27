<?php

namespace App\Models;

use App\Enums\HearingStatus;
use App\Enums\HearingType;
use App\Models\Concerns\HasAuditTrail;
use App\Models\Concerns\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hearing extends Model
{
    use HasFactory, SoftDeletes, HasAuditTrail, Searchable;

    protected array $searchable = [
        'venue',
        'remarks',
        'outcome',
        'court_branch',
    ];

    protected $fillable = [
        'case_id',
        'hearing_type',
        'status',
        'scheduled_date',
        'scheduled_time',
        'venue',
        'court_branch',
        'judge_name',
        'prosecutor_id',
        'assigned_prosecutor_id',
        'outcome',
        'remarks',
        'next_hearing_date',
        'postponement_reason',
        'created_by',
        // Legacy fields (for migration compatibility)
        'date_time',
        'result_status',
    ];

    protected $casts = [
        'hearing_type' => HearingType::class,
        'status' => HearingStatus::class,
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
        'next_hearing_date' => 'date',
        'date_time' => 'datetime', // Legacy
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function case(): BelongsTo
    {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }

    public function prosecutor(): BelongsTo
    {
        return $this->belongsTo(Prosecutor::class, 'prosecutor_id');
    }

    public function assignedProsecutor(): BelongsTo
    {
        return $this->belongsTo(Prosecutor::class, 'assigned_prosecutor_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope to filter upcoming hearings.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('scheduled_date', '>=', today())
                     ->where('status', HearingStatus::Scheduled->value)
                     ->orderBy('scheduled_date')
                     ->orderBy('scheduled_time');
    }

    /**
     * Scope to filter past hearings.
     */
    public function scopePast(Builder $query): Builder
    {
        return $query->where('scheduled_date', '<', today())
                     ->orWhereIn('status', [
                         HearingStatus::Completed->value,
                         HearingStatus::Cancelled->value,
                     ]);
    }

    /**
     * Scope to filter hearings in a date range.
     */
    public function scopeScheduledBetween(Builder $query, ?string $from, ?string $to): Builder
    {
        if ($from) {
            $query->where('scheduled_date', '>=', $from);
        }
        if ($to) {
            $query->where('scheduled_date', '<=', $to);
        }
        return $query;
    }

    /**
     * Scope to filter by hearing type.
     */
    public function scopeOfType(Builder $query, HearingType|string $type): Builder
    {
        $typeValue = $type instanceof HearingType ? $type->value : $type;
        return $query->where('hearing_type', $typeValue);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeWithStatus(Builder $query, HearingStatus|string $status): Builder
    {
        $statusValue = $status instanceof HearingStatus ? $status->value : $status;
        return $query->where('status', $statusValue);
    }

    /**
     * Scope to filter today's hearings.
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('scheduled_date', today());
    }

    /**
     * Scope to filter this week's hearings.
     */
    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('scheduled_date', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ]);
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    /**
     * Get formatted date and time.
     */
    public function getScheduledAtAttribute(): ?string
    {
        if (!$this->scheduled_date) {
            // Fallback to legacy date_time field
            return $this->date_time?->format('M d, Y h:i A');
        }
        
        $date = $this->scheduled_date->format('M d, Y');
        $time = $this->scheduled_time ? $this->scheduled_time->format('h:i A') : '';
        
        return trim("{$date} {$time}");
    }

    /**
     * Check if hearing is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->status !== HearingStatus::Scheduled) {
            return false;
        }
        
        return $this->scheduled_date?->isPast() ?? false;
    }

    /**
     * Get days until hearing.
     */
    public function getDaysUntilAttribute(): ?int
    {
        if (!$this->scheduled_date || $this->scheduled_date->isPast()) {
            return null;
        }
        
        return today()->diffInDays($this->scheduled_date);
    }

    /**
     * Get status badge CSS classes.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status?->badgeClasses() ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get hearing type badge CSS classes.
     */
    public function getTypeBadgeClassAttribute(): string
    {
        return $this->hearing_type?->badgeClasses() ?? 'bg-gray-100 text-gray-800';
    }

    // ==========================================
    // METHODS
    // ==========================================

    /**
     * Mark hearing as completed.
     */
    public function markCompleted(?string $outcome = null, ?string $remarks = null): void
    {
        $this->update([
            'status' => HearingStatus::Completed,
            'outcome' => $outcome,
            'remarks' => $remarks,
        ]);
    }

    /**
     * Postpone hearing.
     */
    public function postpone(string $newDate, ?string $reason = null): void
    {
        $this->update([
            'status' => HearingStatus::Postponed,
            'postponement_reason' => $reason,
            'next_hearing_date' => $newDate,
        ]);
    }

    /**
     * Cancel hearing.
     */
    public function cancel(?string $reason = null): void
    {
        $this->update([
            'status' => HearingStatus::Cancelled,
            'remarks' => $reason,
        ]);
    }

    /**
     * Reschedule hearing (create new hearing and postpone current).
     */
    public function reschedule(string $newDate, ?string $newTime = null, ?string $reason = null): Hearing
    {
        $this->postpone($newDate, $reason);

        return static::create([
            'case_id' => $this->case_id,
            'hearing_type' => $this->hearing_type,
            'status' => HearingStatus::Scheduled,
            'scheduled_date' => $newDate,
            'scheduled_time' => $newTime ?? $this->scheduled_time,
            'venue' => $this->venue,
            'court_branch' => $this->court_branch,
            'judge_name' => $this->judge_name,
            'prosecutor_id' => $this->prosecutor_id,
            'remarks' => "Rescheduled from " . $this->scheduled_date?->format('M d, Y'),
            'created_by' => auth()->id(),
        ]);
    }
}
