<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['company', 'roles'])->latest()->paginate(15);

        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        // the super admin account is not editable from here
        abort_if($user->isSuperAdmin(), 403);

        $roles = $this->assignableRoles();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->isSuperAdmin(), 403);

        $request->validate([
            'role' => ['required', Rule::in($this->assignableRoles())],
        ]);

        // one role per user, so sync instead of assign
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'Role updated for '.$user->name.'.');
    }

    // company users can get any role except super_admin
    private function assignableRoles(): array
    {
        return Role::where('name', '!=', 'super_admin')->pluck('name')->all();
    }
}
