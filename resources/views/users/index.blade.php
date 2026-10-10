<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Users</h2>
    </x-slot>

    <div class="p-6 max-w-5xl mx-auto">

        @if (session('success'))
            <p class="mb-3 text-green-600">{{ session('success') }}</p>
        @endif

        <table class="w-full bg-white border">
            <tr class="bg-gray-100">
                <th class="border p-2">Name</th>
                <th class="border p-2">Email</th>
                <th class="border p-2">Company</th>
                <th class="border p-2">Role</th>
                <th class="border p-2">Action</th>
            </tr>
            @foreach ($users as $user)
                <tr>
                    <td class="border p-2">{{ $user->name }}</td>
                    <td class="border p-2">{{ $user->email }}</td>
                    <td class="border p-2">{{ $user->company->name ?? '—' }}</td>
                    <td class="border p-2">{{ $user->roles->pluck('name')->join(', ') ?: '—' }}</td>
                    <td class="border p-2">
                        @unless ($user->isSuperAdmin())
                            <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600">Change Role</a>
                        @endunless
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
