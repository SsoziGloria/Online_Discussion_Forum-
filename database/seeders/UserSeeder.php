<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the users table.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Amina Admin',
                'username' => 'amina_admin',
                'display_name' => 'Amina Admin',
                'email' => 'admin@devden.test',
                'role' => 'admin',
                'bio' => 'Keeps the forum structure healthy and reviews category strategy.',
                'avatar_url' => 'https://api.dicebear.com/9.x/initials/svg?seed=Amina%20Admin',
                'is_banned' => false,
                'banned_at' => null,
                'reputation' => 4820,
            ],
            [
                'name' => 'Moses Moderator',
                'username' => 'moses_mod',
                'display_name' => 'Moses Moderator',
                'email' => 'moderator@devden.test',
                'role' => 'moderator',
                'bio' => 'Reviews reports, resolves flags, and keeps replies constructive.',
                'avatar_url' => 'https://api.dicebear.com/9.x/initials/svg?seed=Moses%20Moderator',
                'is_banned' => false,
                'banned_at' => null,
                'reputation' => 3250,
            ],
            [
                'name' => 'Sarah Codes',
                'username' => 'sarah_codes',
                'display_name' => 'Sarah Codes',
                'email' => 'sarah@devden.test',
                'role' => 'user',
                'bio' => 'Backend engineer interested in distributed systems and careful migrations.',
                'avatar_url' => 'https://api.dicebear.com/9.x/initials/svg?seed=Sarah%20Codes',
                'is_banned' => false,
                'banned_at' => null,
                'reputation' => 1890,
            ],
            [
                'name' => 'Daniel Query',
                'username' => 'daniel_query',
                'display_name' => 'Daniel Query',
                'email' => 'daniel@devden.test',
                'role' => 'user',
                'bio' => 'Obsesses over SQL plans, indexes, and query budgets.',
                'avatar_url' => 'https://api.dicebear.com/9.x/initials/svg?seed=Daniel%20Query',
                'is_banned' => false,
                'banned_at' => null,
                'reputation' => 1410,
            ],
            [
                'name' => 'Nia Product',
                'username' => 'nia_product',
                'display_name' => 'Nia Product',
                'email' => 'nia@devden.test',
                'role' => 'user',
                'bio' => 'Bridges product requirements with maintainable forum workflows.',
                'avatar_url' => 'https://api.dicebear.com/9.x/initials/svg?seed=Nia%20Product',
                'is_banned' => false,
                'banned_at' => null,
                'reputation' => 960,
            ],
            [
                'name' => 'Isaac Infra',
                'username' => 'isaac_infra',
                'display_name' => 'Isaac Infra',
                'email' => 'isaac@devden.test',
                'role' => 'user',
                'bio' => 'Writes about observability, deployment safety, and rollback planning.',
                'avatar_url' => 'https://api.dicebear.com/9.x/initials/svg?seed=Isaac%20Infra',
                'is_banned' => false,
                'banned_at' => null,
                'reputation' => 1220,
            ],
            [
                'name' => 'Grace Schema',
                'username' => 'grace_schema',
                'display_name' => 'Grace Schema',
                'email' => 'grace@devden.test',
                'role' => 'user',
                'bio' => 'Enjoys schema reviews, validation design, and migration sequencing.',
                'avatar_url' => 'https://api.dicebear.com/9.x/initials/svg?seed=Grace%20Schema',
                'is_banned' => false,
                'banned_at' => null,
                'reputation' => 1540,
            ],
            [
                'name' => 'Peter Archived',
                'username' => 'peter_archived',
                'display_name' => 'Peter Archived',
                'email' => 'peter@devden.test',
                'role' => 'user',
                'bio' => 'An example member seeded in a banned state for moderation views.',
                'avatar_url' => 'https://api.dicebear.com/9.x/initials/svg?seed=Peter%20Archived',
                'is_banned' => true,
                'banned_at' => now()->subDays(10),
                'reputation' => 120,
            ],
        ];

        foreach ($users as $attributes) {
            User::query()->updateOrCreate(
                ['email' => $attributes['email']],
                array_merge($attributes, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
            );
        }
    }
}
