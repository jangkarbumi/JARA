<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Todo Lists') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('lists.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Create New List</a>
            </div>

            @if (session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Lists I Own</h3>
                    <ul>
                        @foreach ($ownedLists as $list)
                            <li class="mb-2 flex justify-between items-center border-b pb-2">
                                <div>
                                    <a href="{{ route('lists.show', $list->id) }}" class="text-blue-600 font-semibold">{{ $list->name }}</a>
                                    <p class="text-sm text-gray-500">{{ $list->description }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('lists.edit', $list->id) }}" class="text-yellow-600">Edit</a>
                                    <form action="{{ route('lists.destroy', $list->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Lists I Collaborate On</h3>
                    <ul>
                        @foreach ($collaboratingLists as $list)
                            @if ($list->owner_id !== auth()->id())
                                <li class="mb-2 flex justify-between items-center border-b pb-2">
                                    <div>
                                        <a href="{{ route('lists.show', $list->id) }}" class="text-blue-600 font-semibold">{{ $list->name }}</a>
                                        <p class="text-sm text-gray-500">Owned by: {{ $list->owner->name }}</p>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
