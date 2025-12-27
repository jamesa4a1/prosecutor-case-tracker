<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Models\Concerns\HasAuditTrail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasAuditTrail;

    // Role constants (kept for backward compatibility)
    public const ROLE_ADMIN = 'Admin';
    public const ROLE_PROSECUTOR = 'Prosecutor';
    public const ROLE_CLERK = 'Clerk';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'office',
        'avatar',
        'notification_preferences',
        'verification_code',
        'verification_code_expires_at',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_code',
    ];

    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
            'email_verified_at' => 'datetime',
            'verification_code_expires_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'notification_preferences' => 'array',
        ];
    }

    // ==========================================
    // ROLE CHECKS
    // ==========================================

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isProsecutor(): bool
    {
        return $this->role === UserRole::Prosecutor;
    }

    public function isClerk(): bool
    {
        return $this->role === UserRole::Clerk;
    }

    public function hasRole(UserRole|string $role): bool
    {
        if ($role instanceof UserRole) {
            return $this->role === $role;
        }
        return $this->role?->value === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->role?->permissions() ?? []);
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function prosecutor(): HasOne
    {
        return $this->hasOne(Prosecutor::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function statusChanges(): HasMany
    {
        return $this->hasMany(StatusHistory::class, 'changed_by');
    }

    public function createdCases(): HasMany
    {
        return $this->hasMany(CaseModel::class, 'created_by');
    }

    public function assignedCases(): HasMany
    {
        return $this->hasMany(CaseModel::class, 'assigned_by');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope to filter active users.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by role.
     */
    public function scopeWithRole(Builder $query, UserRole|string $role): Builder
    {
        $roleValue = $role instanceof UserRole ? $role->value : $role;
        return $query->where('role', $roleValue);
    }

    /**
     * Scope to filter admins.
     */
    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('role', UserRole::Admin->value);
    }

    /**
     * Scope to filter prosecutors.
     */
    public function scopeProsecutors(Builder $query): Builder
    {
        return $query->where('role', UserRole::Prosecutor->value);
    }

    /**
     * Scope to filter clerks.
     */
    public function scopeClerks(Builder $query): Builder
    {
        return $query->where('role', UserRole::Clerk->value);
    }

    /**
     * Search users by name or email.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    /**
     * Get user's initials.
     */
    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->name ?? '');
        $initials = '';
        
        foreach (array_slice($names, 0, 2) as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        
        return $initials ?: 'U';
    }

    /**
     * Get avatar URL with fallback.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        // Return gravatar or UI avatars fallback
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get role badge CSS classes.
     */
    public function getRoleBadgeClassAttribute(): string
    {
        return $this->role?->badgeClasses() ?? 'bg-gray-100 text-gray-800';
    }

    // ==========================================
    // METHODS
    // ==========================================

    /**
     * Record login.
     */
    public function recordLogin(?string $ip = null): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip ?? request()->ip(),
        ]);
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(): void
    {
        $this->update(['is_active' => !$this->is_active]);
    }

    /**
     * Change user role.
     */
    public function changeRole(UserRole $newRole): void
    {
        $this->update(['role' => $newRole]);
    }
}
