<?php

namespace App\Policies;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TodoListPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // user can view their own lists, filtered in controller
    }

    public function view(User $user, TodoList $todoList): bool
    {
        return $user->id === $todoList->owner_id || 
               $todoList->collaborators()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return true; // any authenticated user can create a list
    }

    public function update(User $user, TodoList $todoList): bool
    {
        return $user->id === $todoList->owner_id || 
               $todoList->collaborators()->where('user_id', $user->id)->exists();
    }

    public function delete(User $user, TodoList $todoList): bool
    {
        return $user->id === $todoList->owner_id;
    }

    public function restore(User $user, TodoList $todoList): bool
    {
        return $user->id === $todoList->owner_id;
    }

    public function forceDelete(User $user, TodoList $todoList): bool
    {
        return $user->id === $todoList->owner_id;
    }
}
