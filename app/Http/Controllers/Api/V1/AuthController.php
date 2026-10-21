<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Inscription publique (Rôle élève par défaut, aucun rôle privilégié permis).
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $requestId = $request->header('X-Correlation-ID', (string) Str::uuid());

        $user = DB::transaction(function () use ($validated, $requestId) {
            $user = User::create([
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'password_hash' => Hash::make($validated['password']),
                'status' => 'active',
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'preferred_language' => $validated['preferred_language'] ?? 'fr',
            ]);

            // Attribution exclusive et stricte du rôle "student"
            $user->assignRole('student');

            // Trace d'audit
            AuditLog::record(
                actorUserId: $user->id,
                action: 'auth.registered',
                targetType: 'user',
                targetId: $user->id,
                metadata: ['identifier' => $user->email ?? $user->phone]
            );

            return $user;
        });

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'status' => $user->status,
                    'first_name' => $user->profile?->first_name,
                    'last_name' => $user->profile?->last_name,
                    'roles' => $user->roles->pluck('code'),
                ],
                'token' => $token,
            ],
            'meta' => [
                'request_id' => $requestId,
            ],
        ], 201);
    }

    /**
     * Connexion sécurisée avec protection anti-énumération et journalisation.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $requestId = $request->header('X-Correlation-ID', (string) Str::uuid());
        $login = $validated['login'];

        $user = User::with(['profile', 'roles'])
            ->where('email', $login)
            ->orWhere('phone', $login)
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password_hash)) {
            AuditLog::record(
                actorUserId: $user?->id,
                action: 'auth.login_failed',
                targetType: 'user',
                targetId: $user?->id,
                metadata: ['attempted_login' => $login]
            );

            return response()->json([
                'error' => [
                    'code' => 'INVALID_CREDENTIALS',
                    'message' => 'Identifiant ou mot de passe incorrect.',
                ],
                'meta' => [
                    'request_id' => $requestId,
                ],
            ], 401);
        }

        if ($user->isSuspended()) {
            return response()->json([
                'error' => [
                    'code' => 'ACCOUNT_SUSPENDED',
                    'message' => 'Ce compte a été suspendu.',
                ],
                'meta' => [
                    'request_id' => $requestId,
                ],
            ], 403);
        }

        $user->update(['last_login_at' => now()]);

        $deviceName = $validated['device_name'] ?? 'web-client';
        $token = $user->createToken($deviceName)->plainTextToken;

        AuditLog::record(
            actorUserId: $user->id,
            action: 'auth.login_succeeded',
            targetType: 'user',
            targetId: $user->id,
            metadata: ['device' => $deviceName]
        );

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'status' => $user->status,
                    'first_name' => $user->profile?->first_name,
                    'last_name' => $user->profile?->last_name,
                    'roles' => $user->roles->pluck('code'),
                    'last_login_at' => $user->last_login_at,
                ],
                'token' => $token,
            ],
            'meta' => [
                'request_id' => $requestId,
            ],
        ]);
    }

    /**
     * Déconnexion et révocation du jeton côté serveur.
     */
    public function logout(Request $request): JsonResponse
    {
        $requestId = $request->header('X-Correlation-ID', (string) Str::uuid());
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()->delete();

            AuditLog::record(
                actorUserId: $user->id,
                action: 'auth.logout',
                targetType: 'user',
                targetId: $user->id
            );
        }

        return response()->json([
            'data' => [
                'message' => 'Déconnexion réussie.',
            ],
            'meta' => [
                'request_id' => $requestId,
            ],
        ]);
    }

    /**
     * Demande de réinitialisation de mot de passe (réponse générique anti-énumération).
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['login' => 'required|string']);
        $requestId = $request->header('X-Correlation-ID', (string) Str::uuid());

        // Réponse générique pour ne pas exposer si l'email existe ou non
        return response()->json([
            'data' => [
                'message' => 'Si un compte correspond à ces informations, les instructions ont été transmises.',
            ],
            'meta' => [
                'request_id' => $requestId,
            ],
        ]);
    }
}
