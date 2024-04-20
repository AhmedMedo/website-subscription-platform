<?php

namespace Database\Factories;

use App\Models\Website;
use App\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Website>
 */
class WebsiteFactory extends Factory
{

    protected $model = Website::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'url' => fake()->url(),
            'status' => StatusEnum::ACTIVE->value,
        ];
    }
}
