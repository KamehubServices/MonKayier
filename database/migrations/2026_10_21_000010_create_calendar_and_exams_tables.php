<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DB-10 : Calendrier scolaire officiel Togo, Sessions d'examens et Résultats.
     */
    public function up(): void
    {
        Schema::create('calendar_sources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 255);
            $table->string('source_url', 500)->nullable();
            $table->string('authority', 255); // Ministère des Enseignements Primaire, Secondaire et Technique
            $table->date('checked_at');
            $table->string('verification_status', 30)->default('verified');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('calendar_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict');
            $table->uuid('academic_year_id')->nullable();
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('set null');
            $table->uuid('source_id')->nullable();
            $table->foreign('source_id')->references('id')->on('calendar_sources')->onDelete('set null');
            $table->string('title', 255);
            $table->date('starts_at');
            $table->date('ends_at')->nullable();
            $table->string('event_type', 50); // exam, holiday, term_start, term_end
            $table->string('status', 30)->default('verified');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict');
            $table->uuid('academic_year_id');
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('restrict');
            $table->uuid('education_level_id');
            $table->foreign('education_level_id')->references('id')->on('education_levels')->onDelete('restrict');
            $table->uuid('series_id')->nullable();
            $table->foreign('series_id')->references('id')->on('school_series')->onDelete('set null');
            $table->string('code', 50); // BEPC, BAC1, BAC2, CEPD
            $table->string('title', 255);
            $table->uuid('source_id')->nullable();
            $table->foreign('source_id')->references('id')->on('calendar_sources')->onDelete('set null');
            $table->date('written_exam_starts_on');
            $table->date('written_exam_ends_on')->nullable();
            $table->date('results_proclaimed_on')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('exam_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_session_id');
            $table->foreign('exam_session_id')->references('id')->on('exam_sessions')->onDelete('cascade');
            $table->uuid('student_user_id')->nullable();
            $table->foreign('student_user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('candidate_number', 100);
            $table->string('candidate_name', 255);
            $table->string('decision', 50); // admis, ajourne, admissible
            $table->string('mention', 50)->nullable();
            $table->string('source_document', 255);
            $table->timestamp('verified_at');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
        Schema::dropIfExists('exam_sessions');
        Schema::dropIfExists('calendar_events');
        Schema::dropIfExists('calendar_sources');
    }
};
