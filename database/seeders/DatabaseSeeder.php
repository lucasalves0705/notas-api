<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Folder;
use App\Models\Note;
use App\Models\Record;
use App\Models\Share;
use App\Models\Step;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user1 = User::query()->updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => Hash::make('123456'),
        ]);

        $user2 = User::factory()->create();

        collect([$user1, $user2])->each(function ($user) {
            Folder::factory()
                ->recycle($user)
                ->count(2)
                ->create()
                ->each(function ($folder) use ($user) {
                    Record::factory()
                        ->recycle($user)
                        ->count(fake()->numberBetween(1, 5))
                        ->hasRecordable(Note::factory())
                        ->withFolder($folder)
                        ->create();

                    Record::factory()
                        ->recycle($user)
                        ->count(fake()->numberBetween(1, 5))
                        ->hasRecordable(
                            Task::factory()->has(
                                Step::factory()->count(
                                    fake()->numberBetween(1, 5)
                                )
                            )
                        )
                        ->withFolder($folder)
                        ->create();
                });
        });
    }
}
