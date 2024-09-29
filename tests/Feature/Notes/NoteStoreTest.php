<?php

use App\Models\Folder;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

beforeEach(function () {
    $user = User::factory()->create();
    actingAs($user);
});

test('insere novo registro de nota', function () {
   $user = User::factory()->create();

   $response = actingAs($user)->postJson('api/notes', [
       'title' => fake()->sentence(),
       'content' => fake()->sentence(),
       'import' => fake()->boolean(),
   ]);

   $response->assertStatus(201)
        ->assertJsonStructure(['data' => [
            "id",
            "record_id",
            "title",
            "important",
            "content",
            "created_at",
            "updated_at",
            "deleted_at",
            "folder",
            "user" => [
                "id",
                "name",
                "email",
            ],
        ]])
        ->assertJsonPath('data.user.id', $user->id);
});

describe('validação de campos', function () {
    test('title', function () {
        $response = postJson('api/notes', [
            'content' => fake()->sentence(),
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['title']]);
    });

    test('content', function () {
        $response = postJson('api/notes', [
            'title' => fake()->sentence(),
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['content']]);
    });
});

test('insere em uma pasta', function () {
    $folder = Folder::factory()->create();

    $response = postJson('api/notes', [
        'title' => fake()->sentence(),
        'content' => fake()->sentence(),
        'folder_id' => $folder->id,
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['data' => ['folder' => 'id']])
        ->assertJsonPath('data.folder.id', $folder->id);
});
