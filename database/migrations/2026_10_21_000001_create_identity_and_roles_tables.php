<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * DB-02: Identité, Utilisateurs, Profils, Rôles, Préférences, Terminaux et Journaux d'audit.
     */
    public function up(): void
    {
        // 1. Table users (UUID PK)
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email')->nullable()->unique();
            $table->string('phone', 30)->nullable()->unique();
            $table->string('password_hash');
            $table->string('status', 30)->default('active'); // pending, active, suspended, closed
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Table user_profiles
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->date('birth_date')->nullable();
            $table->string('preferred_language', 10)->default('fr');
            $table->uuid('country_id')->nullable();
            $table->string('avatar_key')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        // 3. Table roles
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 50)->unique(); // student, teacher, parent, school_admin, support, solidarity_agent, admin
            $table->string('label', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 4. Table pivot user_roles
        Schema::create('user_roles', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict');
            $table->uuid('assigned_by')->nullable();
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['user_id', 'role_id']);
        });

        // 5. Table user_preferences
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('locale', 10)->default('fr');
            $table->string('timezone', 50)->default('Africa/Lome');
            $table->json('notification_settings')->default('{}');
            $table->json('accessibility_settings')->default('{}');
            $table->timestamps();
        });

        // 6. Table user_devices
        Schema::create('user_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('device_label', 100)->nullable();
            $table->string('platform', 50); // android, ios, web
            $table->text('fcm_token')->nullable();
            $table->timestamp('last_seen_at')->useCurrent();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 7. Table audit_logs (Traçabilité des actions sensibles)
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('actor_user_id')->nullable();
            $table->foreign('actor_user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('action', 100);
            $table->string('target_type', 100);
            $table->uuid('target_id')->nullable();
            $table->json('metadata')->default('{}');
            $table->string('ip_hash', 64)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['actor_user_id', 'created_at']);
            $table->index(['target_type', 'target_id']);
        });

        // 8. Password reset tokens table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('user_devices');
        Schema::dropIfExists('user_preferences');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('users');
    }
};
