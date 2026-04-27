<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email    = env('ADMIN_SEED_EMAIL', 'admin@simple-eng.io');
        $password = env('ADMIN_SEED_PASSWORD', 'Admin@12345');
        $name     = env('ADMIN_SEED_NAME', 'Site Admin');

        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'name'                 => $name,
                'password'             => Hash::make($password),
                'role'                 => 'admin',
                'is_active'            => true,
                'onboarding_completed' => true,
                'email_verified_at'    => now(),
                'auth_type'            => 'email',
                'referral_code'        => strtoupper(Str::random(8)),
            ]
        );

        $this->command?->info("Admin ready → email: {$admin->email}");
        $this->command?->info("Password (only on fresh create): {$password}");
        $this->command?->warn('Change the password after first login.');
    }
}
