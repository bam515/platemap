<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Visit;
use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
 */
class VisitFactory extends Factory
{
    protected $model = Visit::class;

    public function definition(): array
    {
        $visitedAt = $this->faker->dateTimeBetween('-90 days', 'now');

        $publishedAt = $this->faker->dateTimeBetween($visitedAt, 'now');

        $data = [
            'user_id' => User::factory(),
            'place_id' => Place::factory(),
            'visited_at' => $visitedAt,
            'visibility' => $this->faker->randomElement(['private', 'followers', 'public']),
            'memo' => $this->faker->boolean(60) ? $this->faker->sentence(12) : null,
            'published_at' => $publishedAt,
        ];

        if (Schema::hasColumn('visits', 'is_hidden')) {
            $data['is_hidden'] = false;
        }

        return $data;
    }

    public function draft(): static
    {
        return $this->state(function () {
            $data = ['published_at' => null];

            if (Schema::hasColumn('visits', 'is_hidden')) {
                $data['is_hidden'] = true;
            }

            return $data;
        });
    }

    public function published(): static
    {
        return $this->state(fn() => [
            'published_at' => now()->subMinutes($this->faker->numberBetween(1, 10_000)),
        ]);
    }

    public function visibilityPublic(): static
    {
        return $this->state(fn () => ['visibility' => 'public']);
    }

    public function visibilityFollowers(): static
    {
        return $this->state(fn () => ['visibility' => 'followers']);
    }

    public function visibilityPrivate(): static
    {
        return $this->state(fn () => ['visibility' => 'private']);
    }
}
