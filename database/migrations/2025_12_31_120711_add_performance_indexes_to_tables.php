<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if index exists
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to cases table for faster queries
        Schema::table('cases', function (Blueprint $table) {
            if (!$this->indexExists('cases', 'cases_status_index')) {
                $table->index('status');
            }
            if (!$this->indexExists('cases', 'cases_type_index')) {
                $table->index('type');
            }
            if (!$this->indexExists('cases', 'cases_date_filed_index')) {
                $table->index('date_filed');
            }
        });

        // Add indexes to hearings table
        Schema::table('hearings', function (Blueprint $table) {
            if (!$this->indexExists('hearings', 'hearings_date_time_index')) {
                $table->index('date_time');
            }
            if (!$this->indexExists('hearings', 'hearings_result_status_index')) {
                $table->index('result_status');
            }
        });

        // Add indexes to notes table
        Schema::table('notes', function (Blueprint $table) {
            if (!$this->indexExists('notes', 'notes_case_id_index')) {
                $table->index('case_id');
            }
            if (!$this->indexExists('notes', 'notes_user_id_index')) {
                $table->index('user_id');
            }
        });

        // Add indexes to status_histories table
        Schema::table('status_histories', function (Blueprint $table) {
            if (!$this->indexExists('status_histories', 'status_histories_case_id_index')) {
                $table->index('case_id');
            }
            if (!$this->indexExists('status_histories', 'status_histories_changed_at_index')) {
                $table->index('changed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            if ($this->indexExists('cases', 'cases_status_index')) {
                $table->dropIndex(['status']);
            }
            if ($this->indexExists('cases', 'cases_type_index')) {
                $table->dropIndex(['type']);
            }
            if ($this->indexExists('cases', 'cases_date_filed_index')) {
                $table->dropIndex(['date_filed']);
            }
        });

        Schema::table('hearings', function (Blueprint $table) {
            if ($this->indexExists('hearings', 'hearings_date_time_index')) {
                $table->dropIndex(['date_time']);
            }
            if ($this->indexExists('hearings', 'hearings_result_status_index')) {
                $table->dropIndex(['result_status']);
            }
        });

        Schema::table('notes', function (Blueprint $table) {
            if ($this->indexExists('notes', 'notes_case_id_index')) {
                $table->dropIndex(['case_id']);
            }
            if ($this->indexExists('notes', 'notes_user_id_index')) {
                $table->dropIndex(['user_id']);
            }
        });

        Schema::table('status_histories', function (Blueprint $table) {
            if ($this->indexExists('status_histories', 'status_histories_case_id_index')) {
                $table->dropIndex(['case_id']);
            }
            if ($this->indexExists('status_histories', 'status_histories_changed_at_index')) {
                $table->dropIndex(['changed_at']);
            }
        });
    }
};
