<?php

namespace Tests;

use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // tests using RefreshDatabase get roles and permissions seeded every time
    protected bool $seed = true;

    protected string $seeder = RolesAndPermissionsSeeder::class;
}
