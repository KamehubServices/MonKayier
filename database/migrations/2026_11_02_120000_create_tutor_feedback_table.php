<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Retours des élèves sur les réponses du Tuteur IA (trop long, trop court, pas clair...).
     * Sert à mesurer et ajuster les profils de longueur de réponse.
     */
    public function up(): void
    {
        Schema::create('tutor_feedback', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_user_id')->index();
            $table->string('rating', 30); // good, too_long, too_short, unclear
            $table->string('intent', 40)->nullable();
            $table->string('length_profile', 20)->nullable();
            $table->text('question')->nullable();
            $table->text('answer_excerpt')->nullable();
            $table->string('course_id', 64)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_feedback');
    }
};

