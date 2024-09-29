<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function record(): MorphOne
    {
        return $this->morphOne(Record::class, 'recordable');
    }

    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }
}

