<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['email', 'role', 'company_id', 'invited_by', 'token', 'accepted_at'])]
class Invitation extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'accepted_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isAccepted(): bool
    {
        return $this->accepted_at !== null;
    }

    // which roles this user is allowed to invite
    public static function rolesInvitableBy(User $user): array
    {
        if ($user->can('invite admin to new company')) {
            return ['admin'];
        }

        if ($user->can('invite company users')) {
            return ['admin', 'member'];
        }

        return [];
    }
}
