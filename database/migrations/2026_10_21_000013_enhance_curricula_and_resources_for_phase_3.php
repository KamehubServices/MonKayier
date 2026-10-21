<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Phase 3 : Référentiels scolaires du Togo et gestion des contenus pédagogiques.
     */
    public function up(): void
    {
        // 1. Table des cycles scolaires
        Schema::create('school_cycles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 30)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->integer('pedagogical_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Colonnes additionnelles sur education_levels
        Schema::table('education_levels', function (Blueprint $table) {
            $table->uuid('school_cycle_id')->nullable()->after('cycle');
            $table->boolean('is_exam_level')->default(false)->after('sort_order');
            $table->foreign('school_cycle_id')->references('id')->on('school_cycles')->onDelete('set null');
        });

        // 3. Table d'association niveau-série-matière (coefficients et volumes horaires)
        Schema::create('level_subjects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('education_level_id');
            $table->foreign('education_level_id')->references('id')->on('education_levels')->onDelete('cascade');
            $table->uuid('series_id')->nullable();
            $table->foreign('series_id')->references('id')->on('school_series')->onDelete('cascade');
            $table->uuid('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->boolean('is_mandatory')->default(true);
            $table->integer('coefficient')->default(1);
            $table->decimal('weekly_hours', 4, 1)->default(2.0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Enrichissement de learning_resources
        Schema::table('learning_resources', function (Blueprint $table) {
            $table->string('origin', 30)->default('official')->after('resource_type');
            $table->string('difficulty_level', 20)->default('medium')->after('origin');
            $table->boolean('is_solution_restricted')->default(false)->after('difficulty_level');
            $table->uuid('reviewed_by')->nullable()->after('created_by');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
        });

        // 5. Enrichissement de resource_files (contrôle antivirus et quarantaine)
        Schema::table('resource_files', function (Blueprint $table) {
            $table->string('antivirus_scan_status', 30)->default('clean')->after('access_level');
            $table->boolean('is_quarantined')->default(false)->after('antivirus_scan_status');
        });

        // 6. Table des jobs d'importation batch
        Schema::create('import_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('file_name', 255);
            $table->bigInteger('file_size_bytes')->default(0);
            $table->string('status', 30)->default('pending');
            $table->integer('total_rows')->default(0);
            $table->integer('processed_rows')->default(0);
            $table->integer('error_count')->default(0);
            $table->json('errors_log')->nullable();
            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 7. Table des signalements d'erreurs sur les ressources pédagogiques
        Schema::create('content_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resource_id');
            $table->foreign('resource_id')->references('id')->on('learning_resources')->onDelete('cascade');
            $table->uuid('reported_by');
            $table->foreign('reported_by')->references('id')->on('users')->onDelete('cascade');
            $table->string('report_reason', 255);
            $table->string('severity', 30)->default('low');
            $table->string('status', 30)->default('open');
            $table->uuid('resolved_by')->nullable();
            $table->foreign('resolved_by')->references('id')->on('users')->onDelete('set null');
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_reports');
        Schema::dropIfExists('import_jobs');

        Schema::table('resource_files', function (Blueprint $table) {
            $table->dropColumn(['antivirus_scan_status', 'is_quarantined']);
        });

        Schema::table('learning_resources', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['origin', 'difficulty_level', 'is_solution_restricted', 'reviewed_by']);
        });

        Schema::dropIfExists('level_subjects');

        Schema::table('education_levels', function (Blueprint $table) {
            $table->dropForeign(['school_cycle_id']);
            $table->dropColumn(['school_cycle_id', 'is_exam_level']);
        });

        Schema::dropIfExists('school_cycles');
    }
};
