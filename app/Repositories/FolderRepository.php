<?php

namespace App\Repositories;

use App\Models\Folder;
use Illuminate\Support\Collection;

class FolderRepository
{
    /**
     * Obtém uma lista de pastas do usuário logado.
     */
    public function all(): Collection
    {
        return Folder::query()
            ->orderByDesc('created_at')
            ->where('user_id', auth()->id())
            ->get();
    }

    /**
     * Cria um novo registro de pasta.
     */
    public function store(array $data): Folder
    {
        $folder = new Folder($data);

        $folder->save();
        $folder->refresh();

        return $folder;
    }
}
