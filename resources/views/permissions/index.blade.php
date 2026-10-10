<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Permissions</h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">

        @if (session('success'))
            <p class="mb-3 text-green-600">{{ session('success') }}</p>
        @endif

        <a href="{{ route('permissions.create') }}" class="bg-blue-600 text-white px-3 py-1 rounded">Add Permission</a>

        <table class="w-full mt-4 bg-white border">
            <tr class="bg-gray-100">
                <th class="border p-2">ID</th>
                <th class="border p-2">Name</th>
                <th class="border p-2">Action</th>
            </tr>
            @foreach ($permissions as $permission)
                <tr>
                    <td class="border p-2">{{ $permission->id }}</td>
                    <td class="border p-2">{{ $permission->name }}</td>
                    <td class="border p-2">
                        <a href="{{ route('permissions.edit', $permission->id) }}" class="text-blue-600">Edit</a>

                        <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="inline">
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
