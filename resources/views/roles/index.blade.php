<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Roles</h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">

        @if (session('success'))
            <p class="mb-3 text-green-600">{{ session('success') }}</p>
        @endif

        <a href="{{ route('roles.create') }}" class="bg-blue-600 text-white px-3 py-1 rounded">Add Role</a>

        <table class="w-full mt-4 bg-white border">
            <tr class="bg-gray-100">
                <th class="border p-2">ID</th>
                <th class="border p-2">Name</th>
                <th class="border p-2">Action</th>
            </tr>
            @foreach ($roles as $role)
                <tr>
                    <td class="border p-2">{{ $role->id }}</td>
                    <td class="border p-2">{{ $role->name }}</td>
                    <td class="border p-2">
                        <a href="{{ route('roles.edit', $role->id) }}" class="text-blue-600">Edit</a>

                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</x-app-layout>
