<?php

namespace Database\Factories;

use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Place>
 */
class PlaceFactory extends Factory
{
    protected $model = Place::class;

    public function definition(): array
    {
        $lat = round($this->faker->latitude(33.0, 38.6), 7);
        $lng = round($this->faker->longitude(124.5, 132.0), 7);

        return [
            'name' => $this->faker->company() . ' ' . $this->faker->randomElement(['식당', '카페', '바', '분식', '국밥']),
            'lat' => $this->faker->boolean(85) ? $lat : null,
            'lng' => $this->faker->boolean(85) ? $lng : null,
            'address' => $this->faker->boolean(80) ? $this->faker->address() : null,
            'road_address' => $this->faker->boolean(60) ? $this->faker->streetAddress() : null,
            'source' => null,
            'source_place_id' => null,
            'category' => $this->faker->boolean(60) ? $this->faker->randomElement([
                '한식', '중식', '일식', '양식', '카페', '술집', '디저트', '분식'
            ]) : null,
            'phone' => $this->faker->boolean(45) ? $this->faker->phoneNumber() : null
        ];
    }

    public function external(string $source = 'kakao'): static
    {
        return $this->state(fn() => [
            'source' => $source,
            'source_place_id' => Str::random(20) . '_' . $this->faker->unique()->numerify('########'),
        ]);
    }

    public function kakao(): static
    {
        return $this->external('kakao');
    }

    public function naver(): static
    {
        return $this->external('naver');
    }

    public function google(): static
    {
        return $this->external('google');
    }
}
