<form action="{{ route('lists.store') }}" method="POST" class="flex flex-col gap-3">
    @csrf
    
    <div>
        <x-input-label for="name" value="New Project/List" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required placeholder="e.g. Work, Groceries..." />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <x-primary-button class="w-full justify-center">
        {{ __('Create List') }}
    </x-primary-button>
</form>
