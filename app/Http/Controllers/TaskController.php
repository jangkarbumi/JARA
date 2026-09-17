<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // POST /lists/{list}/tasks - SRS-003: create task
    public function store(Request $request, TaskList $list)
    {
        if (!$list->isAccessibleBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses ke list ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date',
        ]);

        $task = $list->tasks()->create($validated);

        if ($request->wantsJson()) {
            return response()->json($task, 201);
        }
        return back()->with('success', 'Task created successfully.');
    }

    // PUT/PATCH /tasks/{task} - SRS-003: update task
    public function update(Request $request, Task $task)
    {
        if (!$task->list->isAccessibleBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date',
        ]);

        $task->update($validated);

        if ($request->wantsJson()) {
            return response()->json($task);
        }
        return back()->with('success', 'Task updated successfully.');
    }

    // DELETE /tasks/{task} - SRS-003: delete task
    public function destroy(Request $request, Task $task)
    {
        if (!$task->list->isAccessibleBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        $task->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Task deleted']);
        }
        return back()->with('success', 'Task deleted successfully.');
    }

    // PATCH /tasks/{task}/toggle - SRS-004: toggle completion
    public function toggleComplete(Request $request, Task $task)
    {
        if (!$task->list->isAccessibleBy(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        $task->update(['is_completed' => !$task->is_completed]);

        if ($request->wantsJson()) {
            return response()->json($task);
        }
        return back()->with('success', 'Task status updated.');
    }
}
