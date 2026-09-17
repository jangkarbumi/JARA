@props(['task'])

<div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center gap-4 flex-1">
        <!-- Checkbox for toggling status (SRS-004) -->
        <form action="{{ route('tasks.toggle', $task->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="checkbox" onChange="this.form.submit()" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer h-5 w-5" {{ $task->is_completed ? 'checked' : '' }}>
        </form>

        <!-- Task Details -->
        <div class="flex flex-col flex-1">
            <span class="text-gray-900 font-medium text-lg {{ $task->is_completed ? 'line-through text-gray-500' : '' }}">
                {{ $task->name }}
            </span>
            
            <div class="flex items-center gap-3 text-sm mt-1">
                <!-- Priority Badge -->
                @php
                    $priorityColor = match($task->priority) {
                        'high' => 'bg-red-100 text-red-800',
                        'medium' => 'bg-yellow-100 text-yellow-800',
                        'low' => 'bg-green-100 text-green-800',
                        default => 'bg-gray-100 text-gray-800'
                    };
                @endphp
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $priorityColor }} uppercase tracking-wider">
                    {{ $task->priority }}
                </span>

                <!-- Deadline -->
                @if($task->deadline)
                    <span class="text-gray-500 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-2">
        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-900 focus:outline-none transition-colors p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </form>
    </div>
</div>
