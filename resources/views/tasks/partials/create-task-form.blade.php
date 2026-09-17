@props(['list'])

<form action="{{ route('tasks.store', $list->id) }}" method="POST" class="mt-6 space-y-6">
    @csrf

    <div class="flex flex-col sm:flex-row gap-4">
        <!-- Task Name -->
        <div class="flex-1">
            <x-input-label for="name" value="Task Name" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus placeholder="What needs to be done?" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Priority -->
        <div class="sm:w-1/4">
            <x-input-label for="priority" value="Priority" />
            <select id="priority" name="priority" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('priority')" />
        </div>

        <!-- Deadline -->
        <div class="sm:w-1/4">
            <x-input-label for="deadline" value="Deadline (Optional)" />
            <x-text-input id="deadline" name="deadline" type="date" class="mt-1 block w-full" />
            <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
        </div>

        <!-- Submit Button -->
        <div class="flex items-end">
            <x-primary-button class="h-[42px]">
                {{ __('Add') }}
            </x-primary-button>
        </div>
    </div>
</form>
