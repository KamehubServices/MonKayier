<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * DB-01: Référentiels scolaires (Pays, Années, Niveaux, Séries, Matières, Programmes, Thèmes, Objectifs).
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 3)->unique(); // TG
            $table->string('name', 100);
            $table->string('default_locale', 10)->default('fr');
            $table->string('timezone', 50)->default('Africa/Lome');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('academic_years', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict');
            $table->string('label', 50); // 2026-2027
            $table->date('starts_on');
            $table->date('ends_on');
            $table->boolean('is_current')->default(false);
            $table->timestamps();
            $table->unique(['country_id', 'label']);
        });

        Schema::create('education_levels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict');
            $table->string('code', 30); // 6E, 3E, 1ERE, TLE...
            $table->string('name', 100);
            $table->string('cycle', 30)->default('college'); // college, lycee
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['country_id', 'code']);
        });

        Schema::create('school_series', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict');
            $table->uuid('education_level_id')->nullable();
            $table->foreign('education_level_id')->references('id')->on('education_levels')->onDelete('cascade');
            $table->string('code', 30); // A4, CD, F...
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['country_id', 'education_level_id', 'code']);
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict');
            $table->string('code', 50); // MATHS, PCT, SVT, FRANCAIS...
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('icon_name', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['country_id', 'code']);
        });

        Schema::create('curricula', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict');
            $table->uuid('academic_year_id');
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('restrict');
            $table->uuid('education_level_id');
            $table->foreign('education_level_id')->references('id')->on('education_levels')->onDelete('restrict');
            $table->uuid('series_id')->nullable();
            $table->foreign('series_id')->references('id')->on('school_series')->onDelete('set null');
            $table->string('title', 255);
            $table->string('source_url', 500)->nullable();
            $table->string('source_title', 255)->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('status', 30)->default('draft');
            $table->integer('version')->default(1);
            $table->timestamps();
        });

        Schema::create('curriculum_topics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('curriculum_id');
            $table->foreign('curriculum_id')->references('id')->on('curricula')->onDelete('cascade');
            $table->uuid('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('restrict');
            $table->uuid('parent_topic_id')->nullable();
            $table->foreign('parent_topic_id')->references('id')->on('curriculum_topics')->onDelete('cascade');
            $table->string('code', 50)->nullable();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('curriculum_objectives', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('topic_id');
            $table->foreign('topic_id')->references('id')->on('curriculum_topics')->onDelete('cascade');
            $table->string('code', 50)->nullable();
            $table->text('description');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_objectives');
        Schema::dropIfExists('curriculum_topics');
        Schema::dropIfExists('curricula');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('school_series');
        Schema::dropIfExists('education_levels');
        Schema::dropIfExists('academic_years');
        Schema::dropIfExists('countries');
    }
};
