<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_create_a_super_admin_with_no_company(): void
    {
        $this->seed(DatabaseSeeder::class);

        $superAdmin = User::where('email', 'superadmin@example.com')->first();

        $this->assertNotNull($superAdmin);
        $this->assertNull($superAdmin->company_id);
        $this->assertTrue($superAdmin->hasRole('super_admin'));
    }

    public function test_running_the_seeders_twice_does_not_duplicate_anything(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(1, User::where('email', 'superadmin@example.com')->count());
        $this->assertDatabaseCount('model_has_roles', 1);
    }
}
