<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sees_only_short_urls_from_their_own_company(): void
    {
        $companyA = Company::factory()->create();
        $companyB = Company::factory()->create();

        $admin = User::factory()->admin()->forCompany($companyA)->create();
        $memberA = User::factory()->member()->forCompany($companyA)->create();
        $memberB = User::factory()->member()->forCompany($companyB)->create();

        ShortUrl::factory()->for($admin)->create(['original_url' => 'https://admin-own.test']);
        ShortUrl::factory()->for($memberA)->create(['original_url' => 'https://same-company.test']);
        ShortUrl::factory()->for($memberB)->create(['original_url' => 'https://other-company.test']);

        $response = $this->actingAs($admin)->get('/short-urls');

        $response->assertOk();
        $response->assertSee('https://admin-own.test');
        $response->assertSee('https://same-company.test');
        $response->assertDontSee('https://other-company.test');
    }

    public function test_member_sees_only_short_urls_they_created(): void
    {
        $company = Company::factory()->create();

        $member = User::factory()->member()->forCompany($company)->create();
        $otherMember = User::factory()->member()->forCompany($company)->create();

        ShortUrl::factory()->for($member)->create(['original_url' => 'https://mine.test']);
        ShortUrl::factory()->for($otherMember)->create(['original_url' => 'https://not-mine.test']);

        $response = $this->actingAs($member)->get('/short-urls');

        $response->assertOk();
        $response->assertSee('https://mine.test');
        $response->assertDontSee('https://not-mine.test');
    }

    public function test_superadmin_sees_short_urls_from_all_companies(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        ShortUrl::factory()->for(User::factory()->admin()->create())->create(['original_url' => 'https://company-one.test']);
        ShortUrl::factory()->for(User::factory()->member()->create())->create(['original_url' => 'https://company-two.test']);

        $response = $this->actingAs($superAdmin)->get('/short-urls');

        $response->assertOk();
        $response->assertSee('https://company-one.test');
        $response->assertSee('https://company-two.test');
    }
}
