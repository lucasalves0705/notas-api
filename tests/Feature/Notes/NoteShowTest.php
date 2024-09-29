<?php

use App\Models\Note;
use App\Models\Record;
use App\Models\User;
use function Pest\Laravel\actingAs;

test('obtém registro de nota', function () {
    $user = User::factory()->create();
    $record = Record::factory()->for($user)->hasRecordable(Note::factory())->create();
    /** @var Note $note */
    $note = $record->recordable;

    $response = actingAs($user)->getJson('api/notes/'.$note->getKey());
    $response->assertOk()
        ->assertJsonPath('data.id', $note->getKey());
});

test('falha ao obter um registro inexistente', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->getJson('api/notes/'.fake()->uuid());

    $response->assertNotFound();
});
