<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;

use App\Http\Requests\TodoListRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TodoListController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = auth()->user();
        $ownedLists = $user->ownedLists;
        $collaboratingLists = $user->collaboratingLists;
        
        return view('lists.index', compact('ownedLists', 'collaboratingLists'));
    }

    public function create()
    {
        return view('lists.create');
    }

    public function store(TodoListRequest $request)
    {
        DB::transaction(function () use ($request) {
            $list = TodoList::create([
                'name' => $request->name,
                'description' => $request->description,
                'owner_id' => auth()->id(),
            ]);

            $list->collaborators()->attach(auth()->id(), ['role' => 'owner']);
        });

        return redirect()->route('lists.index')->with('success', 'List created successfully.');
    }

    public function show(TodoList $list)
    {
        $this->authorize('view', $list);
        return view('lists.show', compact('list'));
    }

    public function edit(TodoList $list)
    {
        $this->authorize('update', $list);
        return view('lists.edit', compact('list'));
    }

    public function update(TodoListRequest $request, TodoList $list)
    {
        $this->authorize('update', $list);

        $list->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('lists.index')->with('success', 'List updated successfully.');
    }

    public function destroy(TodoList $list)
    {
        $this->authorize('delete', $list);

        DB::transaction(function () use ($list) {
            $list->tasks()->delete();
            $list->collaborators()->detach();
            $list->delete();
        });

        return redirect()->route('lists.index')->with('success', 'List deleted successfully.');
    }
}
