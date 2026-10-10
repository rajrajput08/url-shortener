<?php

namespace App\Http\Requests;

use App\Models\Invitation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', [Invitation::class, $this->input('role')]);
    }

    public function rules(): array
    {
        $rules = [
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
                // only one pending invite per email
                Rule::unique('invitations', 'email')->whereNull('accepted_at'),
            ],
            'role' => ['required', Rule::in(Invitation::rolesInvitableBy($this->user()))],
        ];

        // super admin creates a new company with the invite, admin uses their own
        if ($this->user()->can('invite admin to new company')) {
            $rules['company_name'] = ['required', 'string', 'max:255', 'unique:companies,name'];
        } else {
            $rules['company_name'] = ['prohibited'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already registered or has a pending invitation.',
        ];
    }
}
