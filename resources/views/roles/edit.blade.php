<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Role</h2>
    </x-slot>

    <div class="p-6 max-w-md mx-auto bg-white mt-6">
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Role Name</label>
            <input type="text" name="name" value="{{ old('name', $role->name) }}" class="w-full border p-2 mt-1">
            @error('name')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <label class="block mt-4">Permissions</label>
            @forelse ($permissions as $permission)
                <label class="block">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                        @checked(in_array($permission->name, old('permissions', $role->permissions->pluck('name')->toArray())))>
                    {{ $permission->name }}
                </label>
            @empty
                <p class="text-gray-500 text-sm">No permissions found. <a href="{{ route('permissions.create') }}" class="text-blue-600">Add one</a></p>
            @endforelse

            <button class="bg-blue-600 text-white px-3 py-1 rounded mt-3">Update</button>
            <a href="{{ route('roles.index') }}" class="ml-2">Back</a>
        </form>
    </div>
</x-app-layout>
