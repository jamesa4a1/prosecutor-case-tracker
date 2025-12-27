<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new columns to cases table
        Schema::table('cases', function (Blueprint $table) {
            // Add if not exists
            if (!Schema::hasColumn('cases', 'priority')) {
                $table->string('priority', 20)->default('normal')->after('status');
            }
            if (!Schema::hasColumn('cases', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('cases', 'offense_type')) {
                $table->string('offense_type', 100)->nullable()->after('offense');
            }
            if (!Schema::hasColumn('cases', 'date_closed')) {
                $table->date('date_closed')->nullable()->after('date_filed');
            }
            if (!Schema::hasColumn('cases', 'assigned_by')) {
                $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('cases', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable();
            }
            if (!Schema::hasColumn('cases', 'judge_name')) {
                $table->string('judge_name', 191)->nullable();
            }
            if (!Schema::hasColumn('cases', 'is_confidential')) {
                $table->boolean('is_confidential')->default(false);
            }
            if (!Schema::hasColumn('cases', 'metadata')) {
                $table->json('metadata')->nullable();
            }
            if (!Schema::hasColumn('cases', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('cases', 'deleted_at')) {
                $table->softDeletes();
            }

            // Add indexes
            if (!Schema::hasIndex('cases', 'cases_status_index')) {
                $table->index('status');
            }
            if (!Schema::hasIndex('cases', 'cases_priority_index')) {
                $table->index('priority');
            }
            if (!Schema::hasIndex('cases', 'cases_date_filed_index')) {
                $table->index('date_filed');
            }
        });

        // Add new columns to hearings table
        Schema::table('hearings', function (Blueprint $table) {
            if (!Schema::hasColumn('hearings', 'hearing_type')) {
                $table->string('hearing_type', 50)->default('preliminary')->after('case_id');
            }
            if (!Schema::hasColumn('hearings', 'status')) {
                $table->string('status', 30)->default('scheduled')->after('hearing_type');
            }
            if (!Schema::hasColumn('hearings', 'scheduled_date')) {
                $table->date('scheduled_date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('hearings', 'scheduled_time')) {
                $table->time('scheduled_time')->nullable()->after('scheduled_date');
            }
            if (!Schema::hasColumn('hearings', 'venue')) {
                $table->string('venue', 191)->nullable()->after('court_branch');
            }
            if (!Schema::hasColumn('hearings', 'judge_name')) {
                $table->string('judge_name', 191)->nullable()->after('venue');
            }
            if (!Schema::hasColumn('hearings', 'prosecutor_id')) {
                $table->foreignId('prosecutor_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('hearings', 'outcome')) {
                $table->text('outcome')->nullable();
            }
            if (!Schema::hasColumn('hearings', 'next_hearing_date')) {
                $table->date('next_hearing_date')->nullable();
            }
            if (!Schema::hasColumn('hearings', 'postponement_reason')) {
                $table->text('postponement_reason')->nullable();
            }
            if (!Schema::hasColumn('hearings', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('hearings', 'deleted_at')) {
                $table->softDeletes();
            }

            // Add indexes
            if (!Schema::hasIndex('hearings', 'hearings_scheduled_date_index')) {
                $table->index('scheduled_date');
            }
            if (!Schema::hasIndex('hearings', 'hearings_status_index')) {
                $table->index('status');
            }
        });

        // Add new columns to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip', 45)->nullable();
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }

            // Add index on role
            if (!Schema::hasIndex('users', 'users_role_index')) {
                $table->index('role');
            }
        });

        // Add deleted_at to status_histories if not exists
        Schema::table('status_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('status_histories', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropColumn([
                'priority', 'description', 'offense_type', 'date_closed',
                'assigned_by', 'assigned_at', 'judge_name', 'is_confidential',
                'metadata', 'created_by', 'deleted_at'
            ]);
        });

        Schema::table('hearings', function (Blueprint $table) {
            $table->dropColumn([
                'hearing_type', 'status', 'scheduled_date', 'scheduled_time',
                'venue', 'judge_name', 'prosecutor_id', 'outcome',
                'next_hearing_date', 'postponement_reason', 'created_by', 'deleted_at'
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_login_at', 'last_login_ip', 'deleted_at']);
        });

        Schema::table('status_histories', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
        });
    }
};
