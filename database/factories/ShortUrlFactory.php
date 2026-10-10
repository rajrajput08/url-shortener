<?php

namespace Database\Factories;

use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ShortUrl>
 */
class ShortUrlFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->member(),
            // company always follows the user who created it
            'company_id' => fn (array $attributes) => User::find($attributes['user_id'])->company_id,
            'original_url' => fake()->url(),
            'short_code' => Str::random(6),
        ];
    }
}
