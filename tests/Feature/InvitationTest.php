<?php

namespace Tests\Feature;

use App\Mail\InvitationMail;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_invite_an_admin_into_a_new_company(): void
    {
        Mail::fake();
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)->post('/invitations', [
            'company_name' => 'Acme Corp',
            'email' => 'boss@acme.test',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('invitations.index'));

        $company = Company::where('name', 'Acme Corp')->first();
        $this->assertNotNull($company);
        $this->assertDatabaseHas('invitations', [
            'email' => 'boss@acme.test',
            'role' => 'admin',
            'company_id' => $company->id,
            'invited_by' => $superAdmin->id,
        ]);
        Mail::assertSent(InvitationMail::class);
    }

    public function test_superadmin_cannot_invite_a_member(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)->post('/invitations', [
            'company_name' => 'Acme Corp',
            'email' => 'someone@acme.test',
            'role' => 'member',
        ])->assertForbidden();

        $this->assertDatabaseCount('invitations', 0);
        $this->assertDatabaseCount('companies', 0);
    }

    public function test_admin_can_invite_an_admin_and_a_member_into_their_own_company(): void
    {
        Mail::fake();
        $admin = User::factory()->admin()->create();

        foreach (['admin' => 'second-admin@test.test', 'member' => 'new-member@test.test'] as $role => $email) {
            $this->actingAs($admin)->post('/invitations', [
                'email' => $email,
                'role' => $role,
            ])->assertRedirect(route('invitations.index'));

            $this->assertDatabaseHas('invitations', [
                'email' => $email,
                'role' => $role,
                'company_id' => $admin->company_id,
            ]);
        }

        // admin can't make a new company
        $this->assertDatabaseCount('companies', 1);
    }

    public function test_admin_cannot_pick_a_company_name(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/invitations', [
            'company_name' => 'Sneaky Co',
            'email' => 'x@test.test',
            'role' => 'member',
        ])->assertSessionHasErrors('company_name');
    }

    public function test_member_cannot_invite(): void
    {
        $member = User::factory()->member()->create();

        $this->actingAs($member)->get('/invitations/create')->assertForbidden();

        $this->actingAs($member)->post('/invitations', [
            'email' => 'friend@test.test',
            'role' => 'member',
        ])->assertForbidden();

        $this->assertDatabaseCount('invitations', 0);
    }

    public function test_invited_user_can_accept_and_gets_the_right_role_and_company(): void
    {
        $admin = User::factory()->admin()->create();
        $invitation = Invitation::factory()->create([
            'email' => 'joiner@test.test',
            'role' => 'member',
            'company_id' => $admin->company_id,
            'invited_by' => $admin->id,
        ]);

        $this->get("/invitations/{$invitation->token}/accept")
            ->assertOk()
            ->assertSee('joiner@test.test');

        $response = $this->post("/invitations/{$invitation->token}/accept", [
            'name' => 'Joiner',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ]);

        $response->assertRedirect(route('dashboard'));

        $user = User::where('email', 'joiner@test.test')->first();
        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->hasRole('member'));
        $this->assertSame($admin->company_id, $user->company_id);
        $this->assertNotNull($invitation->fresh()->accepted_at);
    }

    public function test_an_accepted_invitation_cannot_be_used_again(): void
    {
        $invitation = Invitation::factory()->accepted()->create();

        $this->get("/invitations/{$invitation->token}/accept")->assertStatus(410);

        $this->post("/invitations/{$invitation->token}/accept", [
            'name' => 'Again',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])->assertStatus(410);

        $this->assertDatabaseMissing('users', ['email' => $invitation->email]);
    }

    public function test_unknown_token_returns_404(): void
    {
        $this->get('/invitations/does-not-exist/accept')->assertNotFound();
    }
}
