<?php

namespace Database\Factories;

use App\Models\Folder;
use App\Models\Record;
use App\Models\Step;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->text(),
            'deadline' => $this->faker->boolean() ? $this->faker->date() : null,
        ];
    }}
