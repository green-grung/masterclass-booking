<?php

namespace Database\Factories;

use App\Models\Craft;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MasterClassFactory extends Factory
{
    protected $model = MasterClass::class;

    public function definition(): array
    {
        return [
            'craft_id' => Craft::factory(),
            'master_id' => User::factory()->state(['role' => 'master']),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => now()->addDays(rand(1, 30)),
            'time_slot' => $this->faker->randomElement(['9-11', '11-13', '13-15', '15-17']),
            'max_participants' => rand(5, 20),
            'price' => rand(100, 2000),
        ];
    }
}
