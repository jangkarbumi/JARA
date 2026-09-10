<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'list_id',
        'name',
        'is_completed',
        'priority',
        'deadline',
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'deadline' => 'datetime',
        ];
    }

    public function list(): BelongsTo
    {
        return $this->belongsTo(TaskList::class, 'list_id');
    }
}
