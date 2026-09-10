<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskList extends Model
{
    use HasFactory;

    // nama tabel tetap "lists" walau class-nya "TaskList"
    protected $table = 'lists';

    protected $fillable = [
        'name',
        'user_id',
    ];

    // owner dari list
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // semua task di dalam list ini (punya Dev 3, pastikan Task model ada foreign key list_id)
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'list_id');
    }

    // kolaborator (termasuk owner) lewat pivot list_user
    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'list_user', 'list_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    // helper cek apakah user tertentu punya akses (owner atau kolaborator)
    public function isAccessibleBy(User $user): bool
    {
        return $this->user_id === $user->id
            || $this->collaborators()->where('user_id', $user->id)->exists();
    }

    // SRS-006: hitung persentase progres penyelesaian tugas
    public function getProgressPercentageAttribute(): int
    {
        $total = $this->tasks()->count();
        if ($total === 0) {
            return 0;
        }
        $completed = $this->tasks()->where('is_completed', true)->count();
        return (int) round(($completed / $total) * 100);
    }
}