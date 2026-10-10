<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">My Invitations</h2>
    </x-slot>

    <div class="p-6 max-w-5xl mx-auto">

        @if (session('success'))
            <p class="mb-3 text-green-600">{{ session('success') }}</p>
        @endif

        @if (session('acceptUrl'))
            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-300">
                <p class="text-sm">Mail is only written to the log, so here is the accept link for testing:</p>
                <a href="{{ session('acceptUrl') }}" class="text-blue-600 break-all">{{ session('acceptUrl') }}</a>
            </div>
        @endif

        <a href="{{ route('invitations.create') }}" class="bg-blue-600 text-white px-3 py-1 rounded">Invite User</a>

        <table class="w-full mt-4 bg-white border">
            <tr class="bg-gray-100">
                <th class="border p-2">Email</th>
                <th class="border p-2">Role</th>
                <th class="border p-2">Company</th>
                <th class="border p-2">Status</th>
                <th class="border p-2">Sent</th>
            </tr>
            @forelse ($invitations as $invitation)
                <tr>
                    <td class="border p-2">{{ $invitation->email }}</td>
                    <td class="border p-2">{{ $invitation->role }}</td>
                    <td class="border p-2">{{ $invitation->company->name }}</td>
                    <td class="border p-2">
                        @if ($invitation->isAccepted())
                            <span class="text-green-600">Accepted</span>
                        @else
                            <span class="text-gray-500">Pending</span>
                        @endif
                    </td>
                    <td class="border p-2">{{ $invitation->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="border p-2 text-center text-gray-500">You have not sent any invitations yet.</td>
                </tr>
            @endforelse
        </table>

        <div class="mt-4">
            {{ $invitations->links() }}
        </div>
    </div>
</x-app-layout>
