<?php

namespace App\Repositories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class NoteRepository
{
    /**
     * Obtém todas as notas.
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
}
