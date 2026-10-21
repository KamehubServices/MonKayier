<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DB-04 : Ressources pédagogiques, Versions, Fichiers, Pages, Chunks RAG, Sources et Revues.
     */
    public function up(): void
    {
        Schema::create('learning_resources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict');
            $table->uuid('curriculum_id')->nullable();
            $table->foreign('curriculum_id')->references('id')->on('curricula')->onDelete('set null');
            $table->uuid('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('restrict');
            $table->uuid('education_level_id');
            $table->foreign('education_level_id')->references('id')->on('education_levels')->onDelete('restrict');
            $table->string('title', 255);
            $table->text('summary')->nullable();
            $table->string('resource_type', 50)->default('course');
            $table->string('status', 30)->default('draft');
            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('resource_versions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resource_id');
            $table->foreign('resource_id')->references('id')->on('learning_resources')->onDelete('cascade');
            $table->integer('version_number');
            $table->longText('content_text')->nullable();
            $table->string('checksum', 64)->nullable();
            $table->text('change_note')->nullable();
            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['resource_id', 'version_number']);
        });

        Schema::create('resource_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resource_version_id');
            $table->foreign('resource_version_id')->references('id')->on('resource_versions')->onDelete('cascade');
            $table->string('storage_key', 500);
            $table->string('original_name', 255);
            $table->string('mime_type', 100);
            $table->bigInteger('size_bytes');
            $table->string('checksum', 64);
            $table->string('access_level', 30)->default('private');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('resource_pages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resource_version_id');
            $table->foreign('resource_version_id')->references('id')->on('resource_versions')->onDelete('cascade');
            $table->integer('page_number');
            $table->longText('extracted_text')->nullable();
            $table->string('extraction_status', 30)->default('pending');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['resource_version_id', 'page_number']);
        });

        Schema::create('resource_chunks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resource_version_id');
            $table->foreign('resource_version_id')->references('id')->on('resource_versions')->onDelete('cascade');
            $table->uuid('page_id')->nullable();
            $table->foreign('page_id')->references('id')->on('resource_pages')->onDelete('set null');
            $table->integer('chunk_index');
            $table->text('content_text');
            $table->integer('token_estimate')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['resource_version_id', 'chunk_index']);
        });

        Schema::create('resource_sources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resource_id');
            $table->foreign('resource_id')->references('id')->on('learning_resources')->onDelete('cascade');
            $table->string('source_title', 255);
            $table->string('publisher', 255)->nullable();
            $table->string('source_url', 500)->nullable();
            $table->date('accessed_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('verification_status', 30)->default('unverified');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('resource_topics', function (Blueprint $table) {
            $table->uuid('resource_id');
            $table->foreign('resource_id')->references('id')->on('learning_resources')->onDelete('cascade');
            $table->uuid('topic_id');
            $table->foreign('topic_id')->references('id')->on('curriculum_topics')->onDelete('cascade');
            $table->decimal('relevance_score', 3, 2)->default(1.0);
            $table->primary(['resource_id', 'topic_id']);
        });

        Schema::create('resource_reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resource_id');
            $table->foreign('resource_id')->references('id')->on('learning_resources')->onDelete('cascade');
            $table->uuid('reviewer_user_id');
            $table->foreign('reviewer_user_id')->references('id')->on('users')->onDelete('restrict');
            $table->string('status', 30); // approved, rejected, revision_requested
            $table->text('comment')->nullable();
            $table->timestamp('reviewed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_reviews');
        Schema::dropIfExists('resource_topics');
        Schema::dropIfExists('resource_sources');
        Schema::dropIfExists('resource_chunks');
        Schema::dropIfExists('resource_pages');
        Schema::dropIfExists('resource_files');
        Schema::dropIfExists('resource_versions');
        Schema::dropIfExists('learning_resources');
    }
};
