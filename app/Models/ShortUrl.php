<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['company_id', 'user_id', 'original_url', 'short_code'])]
class ShortUrl extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Limit the query to the short urls the given user is allowed to see.
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->can('view all short urls')) {
            return $query;
        }

        if ($user->can('view company short urls')) {
            return $query->where('company_id', $user->company_id);
        }

        if ($user->can('view own short urls')) {
            return $query->where('user_id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }

    /**
     * Generate a random short code that is not already used.
     */
    public static function generateCode(): string
    {
        do {
            $code = Str::random(6);
        } while (static::where('short_code', $code)->exists());

        return $code;
    }
}
