<?php

namespace App\Models;

use App\Enums\CasePriority;
use App\Enums\CaseStatus;
use App\Models\Concerns\HasAuditTrail;
use App\Models\Concerns\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaseModel extends Model
{
    use HasFactory, SoftDeletes, HasAuditTrail, Searchable;

    protected $table = 'cases';

    /**
     * Available case statuses for forms and validation.
     */
    public const STATUSES = [
        'Pending',
        'Under Investigation',
        'Filed',
        'Closed',
        'Archived',
    ];

    /**
     * Available case types for forms and validation.
     */
    public const TYPES = [
        'Criminal',
        'Civil',
        'Special',
    ];

    /**
     * Searchable columns for the Searchable trait.
     */
    protected array $searchable = [
        'case_number',
        'title',
        'offense',
        'complainant',
        'accused',
    ];

    protected $fillable = [
        'case_number',
        'title',
        'description',
        'offense',
        'offense_type',
        'type',
        'status',
        'priority',
        'date_filed',
        'date_closed',
        'complainant',
        'accused',
        'investigating_officer',
        'agency_station',
        'prosecutor_id',
        'assigned_by',
        'assigned_at',
        'next_hearing_at',
        'court_branch',
        'judge_name',
        'is_confidential',
        'notes',
        'metadata',
        'created_by',
    ];

    protected $casts = [
        'status' => CaseStatus::class,
        'priority' => CasePriority::class,
        'date_filed' => 'date',
        'date_closed' => 'date',
        'next_hearing_at' => 'datetime',
        'assigned_at' => 'datetime',
        'is_confidential' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Default values for attributes.
     */
    protected $attributes = [
        'is_confidential' => false,
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function prosecutor(): BelongsTo
    {
        return $this->belongsTo(Prosecutor::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function hearings(): HasMany
    {
        return $this->hasMany(Hearing::class, 'case_id');
    }

    public function upcomingHearings(): HasMany
    {
        return $this->hearings()
            ->where('scheduled_date', '>=', today())
            ->where('status', 'scheduled')
            ->orderBy('scheduled_date');
    }

    public function parties(): HasMany
    {
        return $this->hasMany(CaseParty::class, 'case_id');
    }

    public function complainants(): HasMany
    {
        return $this->parties()->where('party_type', 'complainant');
    }

    public function respondents(): HasMany
    {
        return $this->parties()->where('party_type', 'respondent');
    }

    public function witnesses(): HasMany
    {
        return $this->parties()->where('party_type', 'witness');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CaseDocument::class, 'case_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'case_id');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(StatusHistory::class, 'case_id');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope to filter active cases (not archived or closed).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotIn('status', [
            CaseStatus::Archived->value,
            CaseStatus::Dismissed->value,
            CaseStatus::Resolved->value,
        ]);
    }

    /**
     * Scope to filter by prosecutor.
     */
    public function scopeForProsecutor(Builder $query, int $prosecutorId): Builder
    {
        return $query->where('prosecutor_id', $prosecutorId);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeWithStatus(Builder $query, CaseStatus|string $status): Builder
    {
        $statusValue = $status instanceof CaseStatus ? $status->value : $status;
        return $query->where('status', $statusValue);
    }

    /**
     * Scope to filter by priority.
     */
    public function scopeWithPriority(Builder $query, CasePriority|string $priority): Builder
    {
        $priorityValue = $priority instanceof CasePriority ? $priority->value : $priority;
        return $query->where('priority', $priorityValue);
    }

    /**
     * Scope to filter cases filed within date range.
     */
    public function scopeFiledBetween(Builder $query, ?string $from, ?string $to): Builder
    {
        if ($from) {
            $query->where('date_filed', '>=', $from);
        }
        if ($to) {
            $query->where('date_filed', '<=', $to);
        }
        return $query;
    }

    /**
     * Scope to filter urgent/high priority cases.
     */
    public function scopeUrgent(Builder $query): Builder
    {
        return $query->whereIn('priority', [
            CasePriority::High->value,
            CasePriority::Urgent->value,
        ]);
    }

    /**
     * Scope to include cases with upcoming hearings.
     */
    public function scopeWithUpcomingHearings(Builder $query, int $days = 7): Builder
    {
        return $query->whereHas('hearings', function ($q) use ($days) {
            $q->where('scheduled_date', '>=', today())
              ->where('scheduled_date', '<=', today()->addDays($days))
              ->where('status', 'scheduled');
        });
    }

    // ==========================================
    // ACCESSORS & MUTATORS
    // ==========================================

    /**
     * Get the status badge CSS classes.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status?->badgeClasses() ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get the priority badge CSS classes.
     */
    public function getPriorityBadgeClassAttribute(): string
    {
        return $this->priority?->badgeClasses() ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Check if case is active (not closed/archived).
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status?->isActive() ?? true;
    }

    /**
     * Get the next scheduled hearing.
     */
    public function getNextHearingAttribute()
    {
        return $this->upcomingHearings()->first();
    }

    /**
     * Get days since filing.
     */
    public function getDaysSinceFilingAttribute(): int
    {
        return $this->date_filed?->diffInDays(now()) ?? 0;
    }

    // ==========================================
    // METHODS
    // ==========================================

    /**
     * Assign a prosecutor to this case.
     */
    public function assignProsecutor(Prosecutor $prosecutor, ?User $assignedBy = null): void
    {
        $this->update([
            'prosecutor_id' => $prosecutor->id,
            'assigned_by' => $assignedBy?->id ?? auth()->id(),
            'assigned_at' => now(),
        ]);
    }

    /**
     * Update the case status.
     */
    public function updateStatus(CaseStatus $newStatus, ?string $remarks = null): void
    {
        $oldStatus = $this->status;
        
        $this->update(['status' => $newStatus]);
        
        // Log status change
        $this->statusHistories()->create([
            'old_status' => $oldStatus?->value,
            'new_status' => $newStatus->value,
            'remarks' => $remarks,
            'changed_by' => auth()->id(),
        ]);
    }

    /**
     * Check if case has upcoming hearings.
     */
    public function hasUpcomingHearings(): bool
    {
        return $this->upcomingHearings()->exists();
    }
}
