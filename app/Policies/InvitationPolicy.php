<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;

class InvitationPolicy
{
    // without a role it just checks if the user can invite anyone at all
    public function create(User $user, ?string $role = null): bool
    {
        $allowed = Invitation::rolesInvitableBy($user);

        if ($role === null) {
            return count($allowed) > 0;
        }

        return in_array($role, $allowed);
    }
}
