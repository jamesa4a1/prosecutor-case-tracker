<?php

namespace App\Models\Concerns;

use App\Enums\AuditEvent;
use App\Models\AuditLog;

trait HasAuditTrail
{
    /**
     * Boot the trait to register model event listeners.
     */
    public static function bootHasAuditTrail(): void
    {
        static::created(function ($model) {
            static::logAudit($model, AuditEvent::Created, null, $model->getAuditableAttributes());
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            $original = array_intersect_key($model->getOriginal(), $changes);
            
            // Only log if there are actual changes
            if (!empty($changes)) {
                static::logAudit(
                    $model, 
                    AuditEvent::Updated, 
                    static::filterSensitiveData($original),
                    static::filterSensitiveData($changes)
                );
            }
        });

        static::deleted(function ($model) {
            $event = $model->isForceDeleting() 
                ? AuditEvent::Deleted 
                : AuditEvent::Deleted;
                
            static::logAudit($model, $event, $model->getAuditableAttributes(), null);
        });

        // Handle soft delete restoration if the model uses SoftDeletes
        if (method_exists(static::class, 'restored')) {
            static::restored(function ($model) {
                static::logAudit($model, AuditEvent::Restored, null, $model->getAuditableAttributes());
            });
        }
    }

    /**
     * Log an audit event for the model.
     */
    protected static function logAudit($model, AuditEvent $event, ?array $oldValues, ?array $newValues): void
    {
        // Skip if not in a web request context (e.g., during seeding)
        if (!auth()->check() && !app()->runningInConsole()) {
            return;
        }

        try {
            AuditLog::create([
                'user_id' => auth()->id(),
                'auditable_type' => get_class($model),
                'auditable_id' => $model->getKey(),
                'event' => $event,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
            ]);
        } catch (\Exception $e) {
            // Log the error but don't break the application
            \Log::error('Audit logging failed: ' . $e->getMessage());
        }
    }

    /**
     * Filter out sensitive data from audit logs.
     */
    protected static function filterSensitiveData(array $data): array
    {
        $sensitiveFields = [
            'password',
            'remember_token',
            'verification_code',
            'two_factor_secret',
            'two_factor_recovery_codes',
        ];

        return array_diff_key($data, array_flip($sensitiveFields));
    }

    /**
     * Get attributes that should be audited.
     */
    public function getAuditableAttributes(): array
    {
        $attributes = $this->attributesToArray();
        return static::filterSensitiveData($attributes);
    }

    /**
     * Get the audit logs for this model.
     */
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    /**
     * Log a custom audit event.
     */
    public function logActivity(AuditEvent $event, ?array $oldValues = null, ?array $newValues = null): void
    {
        static::logAudit($this, $event, $oldValues, $newValues);
    }

    /**
     * Log a view event for this model.
     */
    public function logViewed(): void
    {
        static::logAudit($this, AuditEvent::Viewed, null, null);
    }
}
