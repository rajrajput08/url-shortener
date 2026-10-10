<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_short_url(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/short-urls', [
            'original_url' => 'https://laravel.com/docs',
        ]);

        $response->assertRedirect(route('short-urls.index'));
        $this->assertDatabaseHas('short_urls', [
            'original_url' => 'https://laravel.com/docs',
            'user_id' => $admin->id,
            'company_id' => $admin->company_id,
        ]);
    }

    public function test_member_can_create_a_short_url(): void
    {
        $member = User::factory()->member()->create();

        $response = $this->actingAs($member)->post('/short-urls', [
            'original_url' => 'https://example.com/some/long/path',
        ]);

        $response->assertRedirect(route('short-urls.index'));
        $this->assertDatabaseHas('short_urls', [
            'user_id' => $member->id,
            'company_id' => $member->company_id,
        ]);
    }

    public function test_superadmin_cannot_create_a_short_url(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)->get('/short-urls/create')->assertForbidden();

        $this->actingAs($superAdmin)->post('/short-urls', [
            'original_url' => 'https://example.com',
        ])->assertForbidden();

        $this->assertDatabaseCount('short_urls', 0);
    }

    public function test_invalid_url_is_rejected(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/short-urls', [
            'original_url' => 'not a url',
        ])->assertSessionHasErrors('original_url');

        $this->actingAs($admin)->post('/short-urls', [
            'original_url' => 'ftp://example.com/file',
        ])->assertSessionHasErrors('original_url');

        $this->assertDatabaseCount('short_urls', 0);
    }

    public function test_guest_cannot_create_a_short_url(): void
    {
        $this->post('/short-urls', ['original_url' => 'https://example.com'])
            ->assertRedirect('/login');
    }
}
