<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Short URLs</h2>
    </x-slot>

    <div class="p-6 max-w-6xl mx-auto">

        @if (session('success'))
            <p class="mb-3 text-green-600">{{ session('success') }}</p>
        @endif

        @can('create', App\Models\ShortUrl::class)
            <a href="{{ route('short-urls.create') }}" class="bg-blue-600 text-white px-3 py-1 rounded">Create Short URL</a>
        @endcan

        <table class="w-full mt-4 bg-white border">
            <tr class="bg-gray-100">
                <th class="border p-2">Short URL</th>
                <th class="border p-2">Original URL</th>
                <th class="border p-2">Created By</th>
                @can('view all short urls')
                    <th class="border p-2">Company</th>
                @endcan
                <th class="border p-2">Hits</th>
                <th class="border p-2">Created At</th>
            </tr>
            @forelse ($shortUrls as $shortUrl)
                <tr>
                    <td class="border p-2">
                        <a href="{{ route('short-urls.resolve', $shortUrl->short_code) }}" target="_blank" class="text-blue-600">
                            {{ route('short-urls.resolve', $shortUrl->short_code) }}
                        </a>
                    </td>
                    <td class="border p-2 break-all">{{ $shortUrl->original_url }}</td>
                    <td class="border p-2">{{ $shortUrl->user->name }}</td>
                    @can('view all short urls')
                        <td class="border p-2">{{ $shortUrl->company->name }}</td>
                    @endcan
                    <td class="border p-2">{{ $shortUrl->hits }}</td>
                    <td class="border p-2">{{ $shortUrl->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border p-2 text-center text-gray-500">No short URLs found.</td>
                </tr>
            @endforelse
        </table>

        <div class="mt-4">
            {{ $shortUrls->links() }}
        </div>
    </div>
</x-app-layout>
