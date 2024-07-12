<?php

namespace Database\Factories;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'folder_id' => null,
            'title' => $this->faker->text(25),
            'content' => $this->faker->text(),
            'important' => $this->faker->boolean(),
        ];
    }

    public function withFolder($folder = null): self
    {
        if ($folder === null) {
            $folder = Folder::factory();
        }

        return $this->state(function (array $attributes) use ($folder) {
            return [
                'folder_id' => $folder?->id ?? $folder,
            ];
        });
    }
}
