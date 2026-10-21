<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Un utilisateur peut voir son propre profil, ou un admin peut voir tout profil.
     * Interdiction stricte de consultation horizontale entre élèves.
     */
    public function view(User $authenticatedUser, User $targetUser): bool
    {
        return $authenticatedUser->id === $targetUser->id || $authenticatedUser->isAdmin();
    }

    /**
     * Un utilisateur peut modifier uniquement son propre profil.
     */
    public function update(User $authenticatedUser, User $targetUser): bool
    {
        return $authenticatedUser->id === $targetUser->id || $authenticatedUser->isAdmin();
    }

    /**
     * Seul un administrateur système peut suspendre ou supprimer un compte.
     */
    public function delete(User $authenticatedUser, User $targetUser): bool
    {
        return $authenticatedUser->isAdmin() && $authenticatedUser->id !== $targetUser->id;
    }
}
