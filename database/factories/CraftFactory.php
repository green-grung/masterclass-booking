<?php

namespace Database\Factories;

use App\Models\Craft;
use Illuminate\Database\Eloquent\Factories\Factory;

class CraftFactory extends Factory
{
    protected $model = Craft::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'image' => null,
        ];
    }
}