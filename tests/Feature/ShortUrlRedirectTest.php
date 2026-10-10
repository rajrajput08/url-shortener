<?php

namespace Tests\Feature;

use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_the_original_url(): void
    {
        $shortUrl = ShortUrl::factory()->create([
            'original_url' => 'https://laravel.com/docs/routing',
            'short_code' => 'abc123',
        ]);

        $this->get('/s/abc123')->assertRedirect('https://laravel.com/docs/routing');

        $this->assertSame(1, $shortUrl->fresh()->hits);
    }

    public function test_unknown_code_returns_404(): void
    {
        $this->get('/s/nope42')->assertNotFound();
    }
}
