<?php

use App\Models\Note;
use App\Models\Record;
use App\Models\User;
use Illuminate\Support\Arr;
use function Pest\Laravel\actingAs;

test('lista todos as notas do usuário logado', function () {
    $user = User::factory()->create();

    Record::factory()
        ->recycle($user)
        ->hasRecordable(Note::factory())
        ->count(5)
        ->create();

    actingAs($user);

    $response = $this->getJson('/api/notes');

    $response->assertStatus(200)
        ->assertJsonCount(5, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'title', 'important', 'content', 'created_at', 'updated_at', 'deleted_at', 'user', 'folder'
                ]
            ],
        ]);
});


test('não permite acesso para usuários não autenticados', function () {
    $response = $this->getJson('/api/notes');

    $response->assertStatus(401);
});

test('retorna apenas as notas do usuário logado', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Record::factory()
        ->recycle($user1)
        ->hasRecordable(Note::factory())
        ->count(5)
        ->create();

    Record::factory()
        ->recycle($user2)
        ->hasRecordable(Note::factory())
        ->count(5)
        ->create();

    actingAs($user1);
    $response = $this->getJson('/api/notes');

    $authors = Arr::pluck($response->json('data'), 'user.id');
    expect($authors)
        ->each
        ->toEqual($user1->id);
});

test('retorna as notas ordenadas por data de criação', function () {
    $user = User::factory()->create();

    Record::factory()
        ->recycle($user)
        ->hasRecordable(Note::factory())
        ->create();

    sleep(1);

    Record::factory()
        ->recycle($user)
        ->hasRecordable(Note::factory())
        ->create();

    actingAs($user);

    $response = $this->getJson('/api/notes');

    expect($response)->assertSuccessful();

    $sorted = $response->collect('data')->sortByDesc('created_at')->pluck('id');
    expect($response->collect('data')->pluck('id'))->toEqual($sorted);
});
