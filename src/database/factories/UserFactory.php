<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        static $hashedPassword;

        $hashedPassword ??= Hash::make('password');

        return [
            'nickname' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $hashedPassword,
            'provider' => null,
            'provider_id' => null,
            'avatar_url' => $this->faker->boolean(25) ? $this->faker->imageUrl(256, 256, 'people') : null,
            'bio' => $this->faker->boolean(40) ? $this->faker->sentence(10) : null,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn() => [
            'email_verified_at' => null
        ]);
    }

    public function google(): static
    {
        return $this->state(fn() => [
            'provider' => 'google',
            'provider_id' => (string) Str::uuid(),
            'password' => null,
            'email_verified_at' => now()
        ]);
    }
}
