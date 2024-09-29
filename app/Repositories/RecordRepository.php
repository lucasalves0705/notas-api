<?php

namespace App\Repositories;

use App\Models\Note;
use App\Models\Record;
use App\Models\Task;
use Illuminate\Support\Arr;

class RecordRepository
{
    /**
     * Cria um novo Record para o usuário logado.
     */
    public function store(array $data, Note|Task $model): Record
    {
        $record = new Record([
            'title' => $data['title'],
            'user_id' => auth()->id(),
            'important' => Arr::get($data, 'important', false),
            'folder_id' => Arr::get($data, 'folder_id'),
            'recordable_id' => $model->getKey(),
            'recordable_type' => $model->getMorphClass(),
        ]);

        $record->save();
        $record->refresh();

        return $record;
    }
}
