<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $bidderRole = Role::firstOrCreate(['name' => 'bidder']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles([$adminRole]);

        for ($i = 1; $i <= 3; $i++) {
            $bidder = User::firstOrCreate(
                ['email' => "bidder{$i}@example.com"],
                [
                    'name' => "Bidder {$i}",
                    'password' => 'password',
                    'email_verified_at' => now(),
                ]
            );
            $bidder->syncRoles([$bidderRole]);
        }
    }
}
