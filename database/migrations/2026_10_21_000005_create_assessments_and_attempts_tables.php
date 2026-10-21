<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DB-05 : Évaluations, Questions, Options, Réponses, Tentatives d'élèves et Événements.
     */
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resource_id')->nullable();
            $table->foreign('resource_id')->references('id')->on('learning_resources')->onDelete('set null');
            $table->uuid('curriculum_id')->nullable();
            $table->foreign('curriculum_id')->references('id')->on('curricula')->onDelete('set null');
            $table->uuid('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('restrict');
            $table->string('title', 255);
            $table->string('assessment_type', 50)->default('quiz');
            $table->integer('duration_seconds')->nullable();
            $table->string('status', 30)->default('draft');
            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->timestamps();
        });

        Schema::create('assessment_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_id');
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');
            $table->string('question_type', 50)->default('single_choice');
            $table->text('prompt');
            $table->text('explanation')->nullable();
            $table->decimal('points', 8, 2)->default(1.00);
            $table->integer('position')->default(1);
            $table->json('metadata')->default('{}');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('question_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('question_id');
            $table->foreign('question_id')->references('id')->on('assessment_questions')->onDelete('cascade');
            $table->text('option_text');
            $table->integer('position')->default(1);
            $table->boolean('is_correct')->default(false);
            $table->text('explanation')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('question_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('question_id');
            $table->foreign('question_id')->references('id')->on('assessment_questions')->onDelete('cascade');
            $table->json('answer_key');
            $table->json('grading_rule')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('assessment_attempts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_id');
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('restrict');
            $table->uuid('student_user_id');
            $table->foreign('student_user_id')->references('id')->on('users')->onDelete('restrict');
            $table->string('status', 30)->default('in_progress');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('submitted_at')->nullable();
            $table->decimal('score', 8, 2)->nullable();
            $table->decimal('max_score', 8, 2);
            $table->string('idempotency_key', 100);
            $table->integer('assessment_version')->default(1);
            $table->timestamps();
            $table->unique(['student_user_id', 'idempotency_key']);
        });

        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('attempt_id');
            $table->foreign('attempt_id')->references('id')->on('assessment_attempts')->onDelete('cascade');
            $table->uuid('question_id');
            $table->foreign('question_id')->references('id')->on('assessment_questions')->onDelete('restrict');
            $table->json('answer_payload');
            $table->decimal('awarded_points', 8, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('answered_at')->useCurrent();
            $table->unique(['attempt_id', 'question_id']);
        });

        Schema::create('attempt_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('attempt_id');
            $table->foreign('attempt_id')->references('id')->on('assessment_attempts')->onDelete('cascade');
            $table->string('event_type', 50);
            $table->timestamp('occurred_at')->useCurrent();
            $table->json('metadata')->default('{}');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_events');
        Schema::dropIfExists('attempt_answers');
        Schema::dropIfExists('assessment_attempts');
        Schema::dropIfExists('question_answers');
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('assessment_questions');
        Schema::dropIfExists('assessments');
    }
};
