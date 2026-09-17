<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Sidebar: Lists -->
                <div class="w-full md:w-1/3 flex flex-col gap-6">
                    <!-- Create List Form -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Project</h3>
                        @include('lists.partials.create-list-form')
                    </div>

                    <!-- My Lists -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">My Projects</h3>
                        @if($lists->isEmpty())
                            <p class="text-gray-500 text-sm">You don't have any projects yet.</p>
                        @else
                            <ul class="space-y-2">
                                @foreach($lists as $list)
                                    <li>
                                        <a href="{{ route('dashboard', ['list_id' => $list->id]) }}" 
                                           class="block px-4 py-3 rounded-md transition-colors {{ $activeList && $activeList->id === $list->id ? 'bg-indigo-600 text-white' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                                            <div class="flex justify-between items-center">
                                                <span class="font-medium">{{ $list->name }}</span>
                                                <span class="text-xs {{ $activeList && $activeList->id === $list->id ? 'bg-indigo-700 text-indigo-100' : 'bg-gray-200 text-gray-600' }} px-2 py-1 rounded-full">{{ $list->tasks->count() }} tasks</span>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Main Content: Tasks -->
                <div class="w-full md:w-2/3">
                    @if($activeList)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 mb-6">
                            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                                <h3 class="text-2xl font-bold text-gray-900">{{ $activeList->name }}</h3>
                                <!-- Delete list button -->
                                @if($activeList->user_id === auth()->id())
                                <form action="{{ route('lists.destroy', $activeList->id) }}" method="POST" onsubmit="return confirm('Delete this entire project and all tasks?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                            
                            <!-- Add Task Form -->
                            <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-100">
                                @include('tasks.partials.create-task-form', ['list' => $activeList])
                            </div>

                            <!-- Tasks List -->
                            <div class="space-y-3">
                                @forelse($activeList->tasks as $task)
                                    @include('tasks.partials.task-list-item', ['task' => $task])
                                @empty
                                    <div class="text-center py-8 text-gray-500 border border-dashed border-gray-200 rounded-lg">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p>No tasks yet. Create one above!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @else
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10 border border-gray-200 flex flex-col items-center justify-center text-center h-64">
                            <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="text-xl font-medium text-gray-900">Select or create a project</h3>
                            <p class="text-gray-500 mt-2">Create a new project from the sidebar to start adding tasks.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
