<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Create Short URL</h2>
    </x-slot>

    <div class="p-6 max-w-md mx-auto bg-white mt-6">
        <form action="{{ route('short-urls.store') }}" method="POST">
            @csrf

            <label>Original URL</label>
            <input type="url" name="original_url" value="{{ old('original_url') }}" placeholder="https://example.com/long/link" class="w-full border p-2 mt-1">
            @error('original_url')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <button class="bg-blue-600 text-white px-3 py-1 rounded mt-3">Save</button>
            <a href="{{ route('short-urls.index') }}" class="ml-2">Back</a>
        </form>
    </div>
</x-app-layout>
