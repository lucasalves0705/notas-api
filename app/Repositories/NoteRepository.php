<?php

namespace App\Repositories;

use App\Models\Note;

class NoteRepository
{
    public function all()
    {
        return Note::query()->with([
            'record',
            'record.user',
            'record.folder',
        ])->get();
    }
}
