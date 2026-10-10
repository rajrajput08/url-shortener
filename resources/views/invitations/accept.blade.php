<x-guest-layout>
    <h2 class="font-semibold text-lg mb-1">Join {{ $invitation->company->name }}</h2>
    <p class="text-sm text-gray-600 mb-4">You have been invited as {{ $invitation->role }}. Set up your account below.</p>

    <form action="{{ route('invitations.accept.store', $invitation->token) }}" method="POST">
        @csrf

        <label>Email</label>
        <input type="email" value="{{ $invitation->email }}" class="w-full border p-2 mt-1 bg-gray-100" readonly>

        <label class="block mt-3">Name</label>
        <input type="text" name="name" value="{{ old('name') }}" class="w-full border p-2 mt-1" required autofocus>
        @error('name')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror

        <label class="block mt-3">Password</label>
        <input type="password" name="password" class="w-full border p-2 mt-1" required>
        @error('password')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror

        <label class="block mt-3">Confirm Password</label>
        <input type="password" name="password_confirmation" class="w-full border p-2 mt-1" required>

        <button class="bg-blue-600 text-white px-3 py-1 rounded mt-4">Create Account</button>
    </form>
</x-guest-layout>
