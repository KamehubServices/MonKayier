<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DB-06 : Compétences élèves, Plans de révision, Tâches et Sessions d'étude.
     */
    public function up(): void
    {
        Schema::create('student_competencies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_user_id');
            $table->foreign('student_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('objective_id');
            $table->foreign('objective_id')->references('id')->on('curriculum_objectives')->onDelete('cascade');
            $table->decimal('mastery_score', 5, 2)->default(0.00); // 0.00 à 100.00%
            $table->integer('evidence_count')->default(0);
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['student_user_id', 'objective_id']);
        });

        Schema::create('revision_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_user_id');
            $table->foreign('student_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title', 255);
            $table->date('target_date')->nullable();
            $table->string('status', 30)->default('active');
            $table->string('generated_by', 50)->default('system');
            $table->timestamps();
        });

        Schema::create('revision_tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('plan_id');
            $table->foreign('plan_id')->references('id')->on('revision_plans')->onDelete('cascade');
            $table->uuid('objective_id')->nullable();
            $table->foreign('objective_id')->references('id')->on('curriculum_objectives')->onDelete('set null');
            $table->uuid('resource_id')->nullable();
            $table->foreign('resource_id')->references('id')->on('learning_resources')->onDelete('set null');
            $table->timestamp('due_at')->nullable();
            $table->string('status', 30)->default('pending');
            $table->integer('priority')->default(1);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('study_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_user_id');
            $table->foreign('student_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
            $table->string('source', 50)->default('web');
            $table->integer('duration_seconds')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_sessions');
        Schema::dropIfExists('revision_tasks');
        Schema::dropIfExists('revision_plans');
        Schema::dropIfExists('student_competencies');
    }
};
