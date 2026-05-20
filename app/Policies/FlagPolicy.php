<?php

namespace App\Policies;

use App\Models\Flag;
use App\Models\User;

class FlagPolicy
{
    /**
     * Determine if the user can resolve a flag.
     */
    public function resolve(User $user, Flag $flag): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }

    /**
     * Determine if the user can delete a post via flag.
     */
    public function delete(User $user, Flag $flag): bool
    {
        return $user->isAdmin() || $user->isModerator();
    }
}
