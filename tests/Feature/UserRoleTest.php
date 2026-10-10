<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_change_a_users_role(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $member = User::factory()->member()->create();

        $this->actingAs($superAdmin)->put("/users/{$member->id}", ['role' => 'admin'])
            ->assertRedirect(route('users.index'));

        $member->refresh();
        $this->assertTrue($member->hasRole('admin'));
        $this->assertFalse($member->hasRole('member'));
    }

    public function test_nobody_can_be_made_super_admin_from_the_users_page(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $member = User::factory()->member()->create();

        $this->actingAs($superAdmin)->put("/users/{$member->id}", ['role' => 'super_admin'])
            ->assertSessionHasErrors('role');

        $this->assertFalse($member->fresh()->hasRole('super_admin'));
    }

    public function test_admin_and_member_cannot_open_the_management_pages(): void
    {
        foreach ([User::factory()->admin()->create(), User::factory()->member()->create()] as $user) {
            foreach (['/users', '/roles', '/permissions', '/companies'] as $page) {
                $this->actingAs($user)->get($page)->assertForbidden();
            }
        }
    }
}
