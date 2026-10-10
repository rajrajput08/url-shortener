<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Companies</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('companies.create') }}" class="inline-block mb-4 px-4 py-2 bg-gray-800 text-white rounded">
                    + New Company
                </a>

                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">#</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($companies as $company)
                            <tr class="border-b">
                                <td class="py-2">{{ $company->id }}</td>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->is_active ? 'Active' : 'Inactive' }}</td>
                                <td class="flex gap-3 py-2">
                                    <a href="{{ route('companies.edit', $company) }}" class="text-blue-600">Edit</a>
                                    <form action="{{ route('companies.destroy', $company) }}" method="POST"
                                          onsubmit="return confirm('Delete this company?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-center text-gray-500">No companies yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
