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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique();
            $table->string('title');
            $table->string('offense');
            $table->enum('type', ['Criminal', 'Civil', 'Special'])->default('Criminal');
            $table->date('date_filed');
            $table->enum('status', ['Pending', 'Under Investigation', 'Filed', 'Closed', 'Archived'])->default('Pending');
            $table->string('complainant')->nullable();
            $table->string('accused')->nullable();
            $table->string('investigating_officer')->nullable();
            $table->string('agency_station')->nullable();
            $table->foreignId('prosecutor_id')->nullable()->constrained('prosecutors')->onDelete('set null');
            $table->dateTime('next_hearing_at')->nullable();
            $table->string('court_branch')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
