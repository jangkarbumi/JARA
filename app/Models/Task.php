<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'list_id', 'title', 'description', 'priority', 'due_date', 'is_completed'
    ];

    public function list()
    {
        return $this->belongsTo(TodoList::class, 'list_id');
    }
}
