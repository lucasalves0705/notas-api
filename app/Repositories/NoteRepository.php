<?php

namespace App\Repositories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class NoteRepository
{
    public function __construct(
        private readonly RecordRepository $recordRepository,
    )
    {
    }

    /**
     * Obtém todas as notas do usuário logado.
     */
    public function all(): Collection
    {
        return Note::query()
            ->with([
                'record',
                'record.user',
                'record.folder',
            ])
            ->whereHas('record', function (Builder $query) {
                $query->where('user_id', auth()->id());
            })
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Cria um novo registro de nota.
     */
    public function store(array $data): Note
    {
        $note = new Note([
            'content' => $data['content'],
        ]);
        $note->save();

        $this->recordRepository->store($data, $note);

        $note->load([
            'record',
            'record.user',
            'record.folder',
        ]);

        return $note;
    }

    public function find(string|int $id): ?Note
    {
        /** @var Note $note */
        $note = Note::query()
            ->with([
                'record',
                'record.user',
                'record.folder',
            ])
            ->whereHas('record', function (Builder $query) {
                $query->where('user_id', auth()->id());
            })
            ->find($id);

        return $note;
    }
}
