<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sprint>
 */
class SprintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'name' => fake()->name(),
           'description' => fake()->paragraph(1),
           'start_date' => fake()->date(),
           'due_date' => fake()->date(),
           'status' => fake()->randomElement(['In Progress', 'Finished']),
           'priority' => fake()->randomElement(['HIGH', 'MEDIUM', 'LOW']),
           'responsable' => fake()->name(),
        ];
    }


}
