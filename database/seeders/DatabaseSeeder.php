<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Folder;
use App\Models\Share;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user1 = \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('123456'),
        ]);
        $user2 = \App\Models\User::factory()->create();

        collect([$user1, $user2])->each(function ($user) {
            \App\Models\Folder::factory()
                ->recycle($user)
                ->count(2)
                ->create()
                ->each(function ($folder) use ($user) {
                    \App\Models\Note::factory()
                        ->count(fake()->numberBetween(1, 5))
                        ->recycle($user)
                        ->withFolder($folder)
                        ->create();

                    $task = \App\Models\Task::factory()
                        ->count(fake()->numberBetween(1, 5))
                        ->recycle($user)
                        ->withFolder($folder)
                        ->create();

                    \App\Models\Step::factory()
                        ->count(fake()->numberBetween(1, 5))
                        ->recycle($task)
                        ->create();
                });
        });
    }
}
