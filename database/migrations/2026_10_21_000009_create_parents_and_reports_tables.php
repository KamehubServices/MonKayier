<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DB-09 : Espace Parents & Tuteurs, Liaisons, Rapports et Échéances.
     */
    public function up(): void
    {
        Schema::create('parent_student_links', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parent_user_id');
            $table->foreign('parent_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('student_user_id');
            $table->foreign('student_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('status', 30)->default('pending'); // pending, verified, revoked
            $table->string('relationship_type', 50)->default('parent');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
            $table->unique(['parent_user_id', 'student_user_id']);
        });

        Schema::create('parent_link_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_user_id');
            $table->foreign('student_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('code_hash', 64);
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->integer('attempt_count')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('parent_notification_preferences', function (Blueprint $table) {
            $table->uuid('parent_user_id');
            $table->foreign('parent_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('student_user_id');
            $table->foreign('student_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->json('settings')->default('{}');
            $table->primary(['parent_user_id', 'student_user_id']);
        });

        Schema::create('student_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_user_id');
            $table->foreign('student_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('period_start');
            $table->date('period_end');
            $table->json('summary_data');
            $table->timestamp('generated_at')->useCurrent();
        });

        Schema::create('student_deadlines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_user_id');
            $table->foreign('student_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title', 255);
            $table->timestamp('due_at');
            $table->string('source_type', 50)->default('assignment');
            $table->uuid('source_id')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('parent_report_access_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parent_user_id');
            $table->foreign('parent_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('student_user_id');
            $table->foreign('student_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('accessed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_report_access_logs');
        Schema::dropIfExists('student_deadlines');
        Schema::dropIfExists('student_reports');
        Schema::dropIfExists('parent_notification_preferences');
        Schema::dropIfExists('parent_link_codes');
        Schema::dropIfExists('parent_student_links');
    }
};
