<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Step extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'task_id',
        'description',
        'completed',
    ];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
