<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DB-11 : Programme de Solidarité, Justificatifs confidentiels et Décisions.
     */
    public function up(): void
    {
        Schema::create('solidarity_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('applicant_user_id');
            $table->foreign('applicant_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('student_user_id')->nullable();
            $table->foreign('student_user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('status', 30)->default('submitted');
            $table->string('reason_category', 100);
            $table->text('description')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('decided_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('solidarity_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('request_id');
            $table->foreign('request_id')->references('id')->on('solidarity_requests')->onDelete('cascade');
            $table->string('storage_key', 500);
            $table->string('document_type', 100);
            $table->timestamp('uploaded_at')->useCurrent();
            $table->date('retention_until')->nullable();
            $table->string('deletion_status', 30)->default('retained');
        });

        Schema::create('solidarity_decisions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('request_id');
            $table->foreign('request_id')->references('id')->on('solidarity_requests')->onDelete('cascade');
            $table->uuid('decided_by');
            $table->foreign('decided_by')->references('id')->on('users')->onDelete('restrict');
            $table->string('decision', 30); // approved, rejected
            $table->text('public_reason')->nullable();
            $table->text('internal_notes')->nullable();
            $table->integer('granted_duration_days')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('solidarity_reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('request_id');
            $table->foreign('request_id')->references('id')->on('solidarity_requests')->onDelete('cascade');
            $table->uuid('reviewer_user_id');
            $table->foreign('reviewer_user_id')->references('id')->on('users')->onDelete('restrict');
            $table->string('action', 50);
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solidarity_reviews');
        Schema::dropIfExists('solidarity_decisions');
        Schema::dropIfExists('solidarity_documents');
        Schema::dropIfExists('solidarity_requests');
    }
};
