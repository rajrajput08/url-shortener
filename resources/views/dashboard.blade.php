<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                @php($user = auth()->user())

                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Role:</strong> {{ $user->getRoleNames()->join(', ') ?: '—' }}</p>
                <p><strong>Company:</strong> {{ $user->company->name ?? '—' }}</p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('short-urls.index') }}" class="bg-gray-800 text-white px-3 py-1 rounded">Short URLs</a>

                    @can('create', App\Models\ShortUrl::class)
                        <a href="{{ route('short-urls.create') }}" class="bg-blue-600 text-white px-3 py-1 rounded">Create Short URL</a>
                    @endcan

                    @can('create', App\Models\Invitation::class)
                        <a href="{{ route('invitations.create') }}" class="bg-blue-600 text-white px-3 py-1 rounded">Invite User</a>
                        <a href="{{ route('invitations.index') }}" class="bg-gray-800 text-white px-3 py-1 rounded">My Invitations</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
