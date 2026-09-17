<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskList;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get all lists the user owns or collaborates on, eager loading their tasks
        $lists = TaskList::where('user_id', $user->id)
            ->orWhereHas('collaborators', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['owner', 'tasks' => function ($query) {
                // Order tasks by not completed first, then by deadline
                $query->orderBy('is_completed', 'asc')
                      ->orderBy('deadline', 'asc');
            }])
            ->get();

        // Check if a specific list is selected via query param ?list_id=X
        $activeListId = $request->query('list_id');
        $activeList = null;

        if ($activeListId) {
            $activeList = $lists->firstWhere('id', $activeListId);
        } elseif ($lists->isNotEmpty()) {
            $activeList = $lists->first();
        }

        return view('dashboard', compact('lists', 'activeList'));
    }
}
