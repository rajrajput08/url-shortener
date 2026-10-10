<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class AcceptInvitationController extends Controller
{
    public function show(string $token)
    {
        $invitation = $this->findPending($token);

        return view('invitations.accept', compact('invitation'));
    }

    public function store(Request $request, string $token)
    {
        $invitation = $this->findPending($token);

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($request, $invitation) {
            $user = User::create([
                'name' => $request->name,
                'email' => $invitation->email,
                'password' => $request->password,
                'company_id' => $invitation->company_id,
            ]);

            // invite link came to their inbox, so the email counts as verified
            $user->forceFill(['email_verified_at' => now()])->save();
            $user->assignRole($invitation->role);

            $invitation->update(['accepted_at' => now()]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    private function findPending(string $token): Invitation
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->isAccepted()) {
            abort(410, 'This invitation has already been used.');
        }

        return $invitation;
    }
}
