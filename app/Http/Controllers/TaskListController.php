<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskListController extends Controller
{
    // GET /lists - semua list yang bisa diakses user (owner + kolaborator)
    public function index()
    {
        $user = Auth::user();

        $lists = TaskList::where('user_id', $user->id)
            ->orWhereHas('collaborators', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('owner')
            ->get();

        return response()->json($lists);
    }

    // POST /lists - SRS-002: buat list baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $list = TaskList::create([
            'name' => $validated['name'],
            'user_id' => Auth::id(),
        ]);

        // owner otomatis jadi collaborator dengan role owner
        $list->collaborators()->attach(Auth::id(), ['role' => 'owner']);

        return response()->json($list, 201);
    }

    // PUT /lists/{list} - SRS-002: ubah nama list
    public function update(Request $request, TaskList $list)
    {
        $this->authorizeAccess($list, Auth::user());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $list->update(['name' => $validated['name']]);

        return response()->json($list);
    }

    // DELETE /lists/{list} - SRS-002: hapus list (hanya owner)
    public function destroy(TaskList $list)
    {
        if ($list->user_id !== Auth::id()) {
            abort(403, 'Hanya pemilik list yang boleh menghapus.');
        }

        $list->delete();

        return response()->json(['message' => 'List berhasil dihapus.']);
    }

    // POST /lists/{list}/collaborators - SRS-005: tambah kolaborator
    public function addCollaborator(Request $request, TaskList $list)
    {
        // hanya owner yang boleh menambah kolaborator
        if ($list->user_id !== Auth::id()) {
            abort(403, 'Hanya pemilik list yang boleh menambahkan kolaborator.');
        }

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $collaborator = User::where('email', $validated['email'])->firstOrFail();

        if ($list->collaborators()->where('user_id', $collaborator->id)->exists()) {
            return response()->json(['message' => 'User sudah menjadi kolaborator.'], 422);
        }

        $list->collaborators()->attach($collaborator->id, ['role' => 'collaborator']);

        return response()->json(['message' => 'Kolaborator berhasil ditambahkan.']);
    }

    // DELETE /lists/{list}/collaborators/{user} - SRS-005: hapus kolaborator
    public function removeCollaborator(TaskList $list, User $user)
    {
        if ($list->user_id !== Auth::id()) {
            abort(403, 'Hanya pemilik list yang boleh menghapus kolaborator.');
        }

        $list->collaborators()->detach($user->id);

        return response()->json(['message' => 'Kolaborator berhasil dihapus.']);
    }

    // GET /lists/{list}/progress - SRS-006 (bagian backend): agregasi progress
    public function progress(TaskList $list)
    {
        $this->authorizeAccess($list, Auth::user());

        $total = $list->tasks()->count();
        $completed = $list->tasks()->where('is_completed', true)->count();

        $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

        // format response ini yang perlu disepakati dengan Dev 3 (frontend)
        return response()->json([
            'total' => $total,
            'completed' => $completed,
            'percentage' => $percentage,
        ]);
    }

    // helper: pastikan user adalah owner atau kolaborator, kalau bukan -> 403
    private function authorizeAccess(TaskList $list, User $user): void
    {
        if (! $list->isAccessibleBy($user)) {
            abort(403, 'Kamu tidak punya akses ke list ini.');
        }
    }
}