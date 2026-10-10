<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvitationRequest;
use App\Mail\InvitationMail;
use App\Models\Company;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('create', Invitation::class);

        $invitations = Invitation::where('invited_by', $request->user()->id)
            ->with('company')
            ->latest()
            ->paginate(10);

        return view('invitations.index', compact('invitations'));
    }

    public function create(Request $request)
    {
        Gate::authorize('create', Invitation::class);

        $roles = Invitation::rolesInvitableBy($request->user());
        $needsCompany = $request->user()->can('invite admin to new company');

        return view('invitations.create', compact('roles', 'needsCompany'));
    }

    public function store(StoreInvitationRequest $request)
    {
        $user = $request->user();

        $invitation = DB::transaction(function () use ($request, $user) {
            if ($user->can('invite admin to new company')) {
                $companyId = Company::create(['name' => $request->company_name])->id;
            } else {
                $companyId = $user->company_id;
            }

            return Invitation::create([
                'email' => $request->email,
                'role' => $request->role,
                'company_id' => $companyId,
                'invited_by' => $user->id,
                'token' => Str::random(64),
            ]);
        });

        Mail::to($invitation->email)->send(new InvitationMail($invitation));

        // no real mail server, so show the link on screen as well
        return redirect()->route('invitations.index')
            ->with('success', 'Invitation sent to '.$invitation->email.'.')
            ->with('acceptUrl', route('invitations.accept', $invitation->token));
    }
}
