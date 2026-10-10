<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Create the SuperAdmin account using raw SQL only.
     */
    public function run(): void
    {
        $email = env('SUPER_ADMIN_EMAIL', 'superadmin@example.com');
        $password = env('SUPER_ADMIN_PASSWORD', 'password');
        $now = now();

        $existing = DB::selectOne('SELECT id FROM users WHERE email = ?', [$email]);

        if (! $existing) {
            DB::insert(
                'INSERT INTO users (name, email, password, company_id, email_verified_at, created_at, updated_at)
                 VALUES (?, ?, ?, NULL, ?, ?, ?)',
                ['Super Admin', $email, Hash::make($password), $now, $now, $now]
            );
        }

        DB::insert(
            'INSERT INTO model_has_roles (role_id, model_type, model_id)
             SELECT roles.id, ?, users.id
             FROM roles, users
             WHERE roles.name = ? AND roles.guard_name = ? AND users.email = ?
             AND NOT EXISTS (
                 SELECT 1 FROM model_has_roles
                 WHERE model_has_roles.role_id = roles.id
                 AND model_has_roles.model_type = ?
                 AND model_has_roles.model_id = users.id
             )',
            [User::class, 'super_admin', 'web', $email, User::class]
        );
    }
}
