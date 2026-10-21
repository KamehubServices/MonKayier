<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DB-08 : Espace Enseignants, Devoirs, Soumissions, Barèmes, Notations et Suggestions IA.
     */
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('class_id');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->uuid('teacher_user_id');
            $table->foreign('teacher_user_id')->references('id')->on('users')->onDelete('restrict');
            $table->uuid('assessment_id');
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('restrict');
            $table->string('title', 255);
            $table->timestamp('due_at')->nullable();
            $table->string('status', 30)->default('published');
            $table->timestamps();
        });

        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assignment_id');
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
            $table->uuid('student_user_id');
            $table->foreign('student_user_id')->references('id')->on('users')->onDelete('restrict');
            $table->uuid('attempt_id')->nullable();
            $table->foreign('attempt_id')->references('id')->on('assessment_attempts')->onDelete('set null');
            $table->timestamp('submitted_at')->useCurrent();
            $table->string('status', 30)->default('submitted');
            $table->text('teacher_feedback')->nullable();
            $table->unique(['assignment_id', 'student_user_id']);
        });

        Schema::create('grading_rubrics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_id');
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');
            $table->string('title', 255);
            $table->decimal('total_points', 8, 2);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('rubric_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('rubric_id');
            $table->foreign('rubric_id')->references('id')->on('grading_rubrics')->onDelete('cascade');
            $table->string('criterion', 255);
            $table->decimal('max_points', 8, 2);
            $table->integer('position')->default(1);
        });

        Schema::create('submission_grades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('submission_id');
            $table->foreign('submission_id')->references('id')->on('assignment_submissions')->onDelete('cascade');
            $table->uuid('graded_by');
            $table->foreign('graded_by')->references('id')->on('users')->onDelete('restrict');
            $table->decimal('final_score', 8, 2);
            $table->boolean('is_official')->default(true);
            $table->timestamp('graded_at')->useCurrent();
        });

        Schema::create('ai_grading_suggestions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('submission_id');
            $table->foreign('submission_id')->references('id')->on('assignment_submissions')->onDelete('cascade');
            $table->decimal('suggested_score', 8, 2);
            $table->text('justification');
            $table->decimal('confidence_score', 4, 3)->nullable();
            $table->string('status', 30)->default('pending'); // pending, accepted, modified, rejected
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('teacher_reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('suggestion_id');
            $table->foreign('suggestion_id')->references('id')->on('ai_grading_suggestions')->onDelete('cascade');
            $table->uuid('teacher_user_id');
            $table->foreign('teacher_user_id')->references('id')->on('users')->onDelete('restrict');
            $table->string('action', 30); // accepted, modified, rejected
            $table->text('teacher_comment')->nullable();
            $table->timestamp('decided_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_reviews');
        Schema::dropIfExists('ai_grading_suggestions');
        Schema::dropIfExists('submission_grades');
        Schema::dropIfExists('rubric_items');
        Schema::dropIfExists('grading_rubrics');
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('assignments');
    }
};
