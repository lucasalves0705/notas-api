<?php

namespace Database\Factories;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Record>
 */
class RecordFactory extends Factory
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
            'important' => $this->faker->boolean(),
//            'recoradble_id' => null,
//            'recoradble_type' => null,
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

    public function hasRecordable(Factory $factory): self
    {
        return $this->state(function (array $attributes) use ($factory) {
            return [
                'recordable_id' => $factory,
                'recordable_type' => $factory->newModel()->getMorphClass(),
            ];
        });
    }
}
