<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DB-03 : Établissements, Classes, Inscriptions et Matières enseignées.
     */
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict');
            $table->string('name', 255);
            $table->string('school_type', 50)->default('public');
            $table->string('city', 100)->nullable();
            $table->string('status', 30)->default('active');
            $table->timestamps();
        });

        Schema::create('school_memberships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('membership_role', 50);
            $table->string('status', 30)->default('active');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('left_at')->nullable();
            $table->unique(['school_id', 'user_id', 'membership_role']);
        });

        Schema::create('classes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->uuid('academic_year_id');
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('restrict');
            $table->uuid('education_level_id');
            $table->foreign('education_level_id')->references('id')->on('education_levels')->onDelete('restrict');
            $table->uuid('series_id')->nullable();
            $table->foreign('series_id')->references('id')->on('school_series')->onDelete('set null');
            $table->string('label', 100);
            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->timestamps();
        });

        Schema::create('class_memberships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('class_id');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('membership_role', 50)->default('student');
            $table->string('status', 30)->default('active');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('left_at')->nullable();
            $table->unique(['class_id', 'user_id', 'membership_role']);
        });

        Schema::create('class_subjects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('class_id');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->uuid('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('restrict');
            $table->uuid('teacher_user_id')->nullable();
            $table->foreign('teacher_user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['class_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_subjects');
        Schema::dropIfExists('class_memberships');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('school_memberships');
        Schema::dropIfExists('schools');
    }
};
