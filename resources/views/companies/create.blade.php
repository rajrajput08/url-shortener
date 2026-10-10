<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Company</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('companies.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded border-gray-300">
                        @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block mb-1">Status</label>
                        <select name="is_active" class="w-full rounded border-gray-300">
                            <option value="1" @selected(old('is_active', '1') == '1')>Active</option>
                            <option value="0" @selected(old('is_active') == '0')>Inactive</option>
                        </select>
                        @error('is_active') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded">Save</button>
                        <a href="{{ route('companies.index') }}" class="px-4 py-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
