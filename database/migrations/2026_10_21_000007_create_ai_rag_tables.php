<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DB-07 : Assistant IA Pédagogique, RAG, Conversations, Sources, Feedbacks et Évaluations.
     */
    public function up(): void
    {
        Schema::create('ai_models', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('provider', 50); // local, mistral, openai
            $table->string('model_code', 100);
            $table->string('version', 50)->nullable();
            $table->json('capabilities')->default('{}');
            $table->boolean('enabled')->default(true);
            $table->string('config_secret_ref', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('ai_assistants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->json('pedagogical_scope')->default('{}');
            $table->string('status', 30)->default('active');
            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('ai_assistant_resources', function (Blueprint $table) {
            $table->uuid('assistant_id');
            $table->foreign('assistant_id')->references('id')->on('ai_assistants')->onDelete('cascade');
            $table->uuid('resource_id');
            $table->foreign('resource_id')->references('id')->on('learning_resources')->onDelete('cascade');
            $table->primary(['assistant_id', 'resource_id']);
        });

        Schema::create('ai_conversations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('assistant_id')->nullable();
            $table->foreign('assistant_id')->references('id')->on('ai_assistants')->onDelete('set null');
            $table->uuid('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            $table->string('title', 255)->nullable();
            $table->string('status', 30)->default('active');
            $table->timestamps();
        });

        Schema::create('ai_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('conversation_id');
            $table->foreign('conversation_id')->references('id')->on('ai_conversations')->onDelete('cascade');
            $table->string('role', 20); // user, assistant
            $table->text('content');
            $table->string('status', 30)->default('completed');
            $table->uuid('model_id')->nullable();
            $table->foreign('model_id')->references('id')->on('ai_models')->onDelete('set null');
            $table->integer('input_tokens')->nullable();
            $table->integer('output_tokens')->nullable();
            $table->integer('latency_ms')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('ai_message_sources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('message_id');
            $table->foreign('message_id')->references('id')->on('ai_messages')->onDelete('cascade');
            $table->uuid('resource_chunk_id');
            $table->foreign('resource_chunk_id')->references('id')->on('resource_chunks')->onDelete('restrict');
            $table->string('citation_label', 100)->nullable();
            $table->decimal('relevance_score', 5, 4)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('ai_feedback', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('message_id');
            $table->foreign('message_id')->references('id')->on('ai_messages')->onDelete('cascade');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('rating'); // +1 ou -1
            $table->string('reason', 100)->nullable();
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['message_id', 'user_id']);
        });

        Schema::create('ai_usage_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->uuid('model_id')->nullable();
            $table->foreign('model_id')->references('id')->on('ai_models')->onDelete('set null');
            $table->string('event_type', 50);
            $table->integer('tokens')->default(0);
            $table->decimal('estimated_cost', 10, 6)->nullable();
            $table->timestamp('occurred_at')->useCurrent();
        });

        Schema::create('ai_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('job_type', 50);
            $table->string('status', 30)->default('pending');
            $table->string('request_ref', 255)->nullable();
            $table->string('result_ref', 255)->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->string('error_code', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_jobs');
        Schema::dropIfExists('ai_usage_events');
        Schema::dropIfExists('ai_feedback');
        Schema::dropIfExists('ai_message_sources');
        Schema::dropIfExists('ai_messages');
        Schema::dropIfExists('ai_conversations');
        Schema::dropIfExists('ai_assistant_resources');
        Schema::dropIfExists('ai_assistants');
        Schema::dropIfExists('ai_models');
    }
};
