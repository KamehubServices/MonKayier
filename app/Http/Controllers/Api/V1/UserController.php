<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Profil de l'utilisateur connecté.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load(['profile', 'roles', 'preferences']);
        $requestId = $request->header('X-Correlation-ID', (string) Str::uuid());

        return response()->json([
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
                'status' => $user->status,
                'profile' => [
                    'first_name' => $user->profile?->first_name,
                    'last_name' => $user->profile?->last_name,
                    'full_name' => $user->profile?->full_name,
                    'birth_date' => $user->profile?->birth_date?->format('Y-m-d'),
                    'preferred_language' => $user->profile?->preferred_language,
                    'avatar_key' => $user->profile?->avatar_key,
                    'bio' => $user->profile?->bio,
                ],
                'roles' => $user->roles->pluck('code'),
                'preferences' => $user->preferences?->notification_settings ?? [],
                'created_at' => $user->created_at?->toISOString(),
            ],
            'meta' => [
                'request_id' => $requestId,
            ],
        ]);
    }

    /**
     * Mise à jour du profil de l'utilisateur connecté (champs autorisés uniquement).
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $requestId = $request->header('X-Correlation-ID', (string) Str::uuid());

        $profile = $user->profile;
        if ($profile) {
            $profile->update($validated);
        } else {
            $user->profile()->create($validated);
        }

        AuditLog::record(
            actorUserId: $user->id,
            action: 'profile.updated',
            targetType: 'user_profile',
            targetId: $user->id,
            metadata: array_keys($validated)
        );

        return response()->json([
            'data' => [
                'message' => 'Profil mis à jour avec succès.',
                'profile' => $user->fresh('profile')->profile,
            ],
            'meta' => [
                'request_id' => $requestId,
            ],
        ]);
    }
}
