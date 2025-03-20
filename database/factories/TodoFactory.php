<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'assignee' => $this->faker->name(),
            'due_date' => $this->faker->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
            'time_tracked' => $this->faker->numberBetween(30, 300), // Waktu dalam menit
            'status' => $this->faker->randomElement(['pending', 'open', 'in_progress', 'completed']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
