<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DB-12 : Notifications, Synchronisation hors-ligne, Paramètres système et Modération.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('category', 50);
            $table->string('title', 255);
            $table->text('body');
            $table->string('action_url', 500)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('notification_deliveries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('notification_id');
            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade');
            $table->string('channel', 30); // in_app, push, email, sms
            $table->string('delivery_status', 30)->default('pending');
            $table->integer('attempts')->default(0);
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('channel', 30);
            $table->string('category', 50);
            $table->boolean('is_enabled')->default(true);
            $table->primary(['user_id', 'channel', 'category']);
        });

        Schema::create('sync_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('client_device_id', 100);
            $table->timestamp('last_synced_at')->useCurrent();
            $table->boolean('is_revoked')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['user_id', 'client_device_id']);
        });

        Schema::create('sync_operations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('device_id');
            $table->foreign('device_id')->references('id')->on('sync_devices')->onDelete('cascade');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('client_operation_uuid');
            $table->string('operation_type', 50);
            $table->json('payload');
            $table->string('status', 30)->default('applied');
            $table->timestamp('applied_at')->useCurrent();
            $table->unique(['device_id', 'client_operation_uuid']);
        });

        Schema::create('sync_conflicts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('operation_id');
            $table->foreign('operation_id')->references('id')->on('sync_operations')->onDelete('cascade');
            $table->string('conflict_type', 50);
            $table->json('server_data');
            $table->json('client_data');
            $table->string('resolution_status', 30)->default('pending');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('system_settings', function (Blueprint $table) {
            $table->string('key', 100)->primary();
            $table->json('value');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamp('updated_at')->useCurrent();
        });

        Schema::create('feature_flags', function (Blueprint $table) {
            $table->string('name', 100)->primary();
            $table->boolean('is_enabled')->default(false);
            $table->json('rules')->default('{}');
            $table->timestamp('updated_at')->useCurrent();
        });

        Schema::create('data_exports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('status', 30)->default('pending');
            $table->string('file_storage_key', 500)->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('deletion_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('status', 30)->default('pending');
            $table->date('scheduled_for');
            $table->timestamp('executed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deletion_requests');
        Schema::dropIfExists('data_exports');
        Schema::dropIfExists('feature_flags');
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('sync_conflicts');
        Schema::dropIfExists('sync_operations');
        Schema::dropIfExists('sync_devices');
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('notification_deliveries');
        Schema::dropIfExists('notifications');
    }
};
