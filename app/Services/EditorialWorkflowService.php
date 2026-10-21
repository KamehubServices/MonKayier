<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\LearningResource;
use App\Models\ResourceReview;
use App\Models\ResourceVersion;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;

class EditorialWorkflowService
{
    /**
     * Submit a resource for review.
     */
    public function submitForReview(LearningResource $resource, User $author): LearningResource
    {
        if ($resource->created_by !== $author->id && !$author->isAdmin()) {
            throw new AuthorizationException("Seul l'auteur ou un administrateur peut soumettre cette ressource.");
        }

        if ($resource->status === 'published') {
            throw ValidationException::withMessages([
                'status' => ['La ressource est déjà publiée.'],
            ]);
        }

        $resource->update([
            'status' => 'review',
        ]);

        AuditLog::record(
            $author->id,
            'RESOURCE_SUBMITTED_FOR_REVIEW',
            'LearningResource',
            $resource->id,
            ['title' => $resource->title]
        );

        return $resource->fresh();
    }

    /**
     * Review a resource (Approve, Reject, Request Changes).
     * Rule: An author cannot approve their own resource.
     */
    public function reviewResource(
        LearningResource $resource,
        User $reviewer,
        string $decision,
        ?string $comment = null
    ): ResourceReview {
        $validDecisions = ['approved', 'rejected', 'revision_requested'];
        if (!in_array($decision, $validDecisions, true)) {
            throw ValidationException::withMessages([
                'decision' => ["La décision doit être l'une des suivantes : " . implode(', ', $validDecisions)],
            ]);
        }

        // Règle impérative de séparation des rôles : l'auteur ne peut pas approuver sa propre ressource
        if ($resource->created_by === $reviewer->id && $decision === 'approved') {
            throw new AuthorizationException("L'auteur d'une ressource ne peut pas auto-valider son propre contenu officiel.");
        }

        $review = ResourceReview::create([
            'resource_id' => $resource->id,
            'reviewer_user_id' => $reviewer->id,
            'status' => $decision,
            'comment' => $comment,
            'reviewed_at' => now(),
        ]);

        if ($decision === 'approved') {
            $resource->update([
                'status' => 'approved',
                'reviewed_by' => $reviewer->id,
            ]);
        } elseif ($decision === 'rejected') {
            $resource->update([
                'status' => 'rejected',
                'reviewed_by' => $reviewer->id,
            ]);
        } elseif ($decision === 'revision_requested') {
            $resource->update([
                'status' => 'draft',
            ]);
        }

        AuditLog::record(
            $reviewer->id,
            'RESOURCE_REVIEWED',
            'LearningResource',
            $resource->id,
            [
                'decision' => $decision,
                'comment' => $comment,
            ]
        );

        return $review;
    }

    /**
     * Publish an approved resource.
     * Rule: A resource can only be published if it has been validated/approved.
     */
    public function publishResource(LearningResource $resource, User $publisher): LearningResource
    {
        if ($resource->status !== 'approved') {
            throw ValidationException::withMessages([
                'status' => ["Impossible de publier une ressource sans validation préalable (statut actuel : {$resource->status})."],
            ]);
        }

        $resource->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Assurer qu'au moins une version est enregistrée
        if ($resource->versions()->count() === 0) {
            ResourceVersion::create([
                'resource_id' => $resource->id,
                'version_number' => 1,
                'content_text' => $resource->summary,
                'checksum' => hash('sha256', $resource->title . $resource->summary),
                'change_note' => 'Publication initiale validée.',
                'created_by' => $publisher->id,
                'created_at' => now(),
            ]);
        }

        AuditLog::record(
            $publisher->id,
            'RESOURCE_PUBLISHED',
            'LearningResource',
            $resource->id,
            ['published_at' => now()->toIso8601String()]
        );

        return $resource->fresh();
    }

    /**
     * Archive or emergency unpublish a resource.
     */
    public function archiveResource(LearningResource $resource, User $user, string $reason): LearningResource
    {
        $resource->update([
            'status' => 'archived',
        ]);

        AuditLog::record(
            $user->id,
            'RESOURCE_ARCHIVED',
            'LearningResource',
            $resource->id,
            ['reason' => $reason]
        );

        return $resource->fresh();
    }
}
