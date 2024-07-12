<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Share extends Pivot
{
    protected $fillable = [
        'folder_id',
        'user_id',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
