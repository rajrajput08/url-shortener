<?php

namespace App\Policies;

use App\Models\User;

class ShortUrlPolicy
{
    public function create(User $user): bool
    {
        return $user->can('create short urls');
    }
}
