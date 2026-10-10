<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Add Permission</h2>
    </x-slot>

    <div class="p-6 max-w-md mx-auto bg-white mt-6">
        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf

            <label>Permission Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border p-2 mt-1">
            @error('name')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <button class="bg-blue-600 text-white px-3 py-1 rounded mt-3">Save</button>
            <a href="{{ route('permissions.index') }}" class="ml-2">Back</a>
        </form>
    </div>
</x-app-layout>
