<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Change Role</h2>
    </x-slot>

    <div class="p-6 max-w-md mx-auto bg-white mt-6">
        <p class="mb-3">{{ $user->name }} ({{ $user->email }})</p>

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Role</label>
            <select name="role" class="w-full border p-2 mt-1">
                @foreach ($roles as $role)
                    <option value="{{ $role }}" @selected(old('role', $user->roles->first()?->name) == $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            @error('role')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <button class="bg-blue-600 text-white px-3 py-1 rounded mt-3">Update</button>
            <a href="{{ route('users.index') }}" class="ml-2">Back</a>
        </form>
    </div>
</x-app-layout>
