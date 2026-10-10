<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function forCompany(Company $company): static
    {
        return $this->state(fn () => ['company_id' => $company->id]);
    }

    public function superAdmin(): static
    {
        return $this->state(fn () => ['company_id' => null])
            ->afterCreating(fn (User $user) => $user->assignRole('super_admin'));
    }

    // admin and member get a fresh company unless forCompany() is chained after
    public function admin(): static
    {
        return $this->state(fn () => ['company_id' => Company::factory()])
            ->afterCreating(fn (User $user) => $user->assignRole('admin'));
    }

    public function member(): static
    {
        return $this->state(fn () => ['company_id' => Company::factory()])
            ->afterCreating(fn (User $user) => $user->assignRole('member'));
    }
}
