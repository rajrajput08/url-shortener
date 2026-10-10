<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Invite User</h2>
    </x-slot>

    <div class="p-6 max-w-md mx-auto bg-white mt-6">
        <form action="{{ route('invitations.store') }}" method="POST">
            @csrf

            @if ($needsCompany)
                <label>Company Name</label>
                <input type="text" name="company_name" value="{{ old('company_name') }}" class="w-full border p-2 mt-1">
                <p class="text-gray-500 text-xs">A new company will be created for this admin.</p>
                @error('company_name')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            @endif

            <label class="block mt-3">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2 mt-1">
            @error('email')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <label class="block mt-3">Role</label>
            <select name="role" class="w-full border p-2 mt-1">
                @foreach ($roles as $role)
                    <option value="{{ $role }}" @selected(old('role') == $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            @error('role')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror

            <button class="bg-blue-600 text-white px-3 py-1 rounded mt-3">Send Invite</button>
            <a href="{{ route('invitations.index') }}" class="ml-2">Back</a>
        </form>
    </div>
</x-app-layout>
