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
        Schema::create('student_report_cards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_user_id')->index();
            $table->string('title')->nullable();
            $table->string('school_year', 50)->default('2026-2027');
            $table->string('class_level', 50)->default('Terminale D');
            $table->string('term', 50)->default('Trimestre 1');
            $table->string('file_path', 500)->nullable();
            $table->string('file_name')->nullable();
            $table->decimal('overall_average', 4, 2)->nullable();
            $table->text('grades_json')->nullable();
            $table->string('ai_status', 50)->default('pending_analysis'); // pending_analysis, analyzed, manual_verified
            $table->text('ai_summary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_report_cards');
    }
};
