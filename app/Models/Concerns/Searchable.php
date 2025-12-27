<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    /**
     * Scope to search across multiple columns.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        $searchableColumns = $this->getSearchableColumns();
        
        return $query->where(function (Builder $query) use ($search, $searchableColumns) {
            foreach ($searchableColumns as $column) {
                if (str_contains($column, '.')) {
                    // Handle relationship columns (e.g., 'parties.name')
                    [$relation, $relationColumn] = explode('.', $column, 2);
                    $query->orWhereHas($relation, function (Builder $q) use ($relationColumn, $search) {
                        $q->where($relationColumn, 'like', "%{$search}%");
                    });
                } else {
                    $query->orWhere($column, 'like', "%{$search}%");
                }
            }
        });
    }

    /**
     * Get the columns that should be searchable.
     * Override this method in models to customize searchable columns.
     */
    protected function getSearchableColumns(): array
    {
        return $this->searchable ?? ['id'];
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeDateBetween(Builder $query, string $column, ?string $from, ?string $to): Builder
    {
        if ($from) {
            $query->where($column, '>=', $from);
        }
        
        if ($to) {
            $query->where($column, '<=', $to);
        }
        
        return $query;
    }

    /**
     * Scope to filter by multiple values.
     */
    public function scopeWhereInColumn(Builder $query, string $column, mixed $values): Builder
    {
        if (empty($values)) {
            return $query;
        }
        
        $values = is_array($values) ? $values : [$values];
        
        return $query->whereIn($column, $values);
    }
}
