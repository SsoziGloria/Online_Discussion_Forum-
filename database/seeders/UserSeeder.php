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
                'name' => 'Gloria Admin',
                'username' => 'gloria_admin',
                'display_name' => 'Gloria Admin',
                'email' => 'gloria@gmail.com',
                'role' => 'admin',
                'bio' => 'Keeps the forum welcoming and makes sure conversations stay useful.',
                'avatar_url' => 'https://api.dicebear.com/9.x/thumbs/svg?seed=Gloria%20Admin',
                'is_banned' => false,
                'banned_at' => null,
                'reputation' => 4820,
            ],
            [
                'name' => 'Margaret Moderator',
                'username' => 'margaret_mod',
                'display_name' => 'Margaret Moderator',
                'email' => 'margaret@gmail.com',
                'role' => 'moderator',
                'bio' => 'Keeps replies kind, clear, and easy to follow.',
                'avatar_url' => 'https://api.dicebear.com/9.x/thumbs/svg?seed=Margaret%20Moderator',
                'is_banned' => false,
                'banned_at' => null,
                'reputation' => 3250,
            ],
            [
                'name' => 'Elly Notes',
                'username' => 'elly_notes',
                'display_name' => 'Elly Notes',
                'email' => 'elly@gmail.com',
                'role' => 'user',
                'bio' => 'Likes light app talk, neat ideas, and friendly forum threads.',
                'avatar_url' => 'https://api.dicebear.com/9.x/thumbs/svg?seed=Elly%20Notes',
                'is_banned' => false,
                'banned_at' => null,
                'reputation' => 1890,
            ],
            [
                'name' => 'Paulla Reads',
                'username' => 'paulla_reads',
                'display_name' => 'Paulla Reads',
                'email' => 'paulla@gmail.com',
                'role' => 'user',
                'bio' => 'Enjoys calm discussion threads and small product ideas.',
                'avatar_url' => 'https://api.dicebear.com/9.x/thumbs/svg?seed=Paulla%20Reads',
                'is_banned' => false,
                'banned_at' => null,
                'reputation' => 1410,
            ],
            [
                'name' => 'Robin Lane',
                'username' => 'robin_lane',
                'display_name' => 'Robin Lane',
                'email' => 'robin@gmail.com',
                'role' => 'user',
                'bio' => 'Shares short thoughts on community habits and forum design.',
                'avatar_url' => 'https://api.dicebear.com/9.x/thumbs/svg?seed=Robin%20Lane',
                'is_banned' => false,
                'banned_at' => null,
                'reputation' => 960,
            ],
            [
                'name' => 'Kai Brooks',
                'username' => 'kai_brooks',
                'display_name' => 'Kai Brooks',
                'email' => 'kai@gmail.com',
                'role' => 'user',
                'bio' => 'Likes practical updates, release notes, and small improvements.',
                'avatar_url' => 'https://api.dicebear.com/9.x/thumbs/svg?seed=Kai%20Brooks',
                'is_banned' => false,
                'banned_at' => null,
                'reputation' => 1220,
            ],
            [
                'name' => 'Lena Hart',
                'username' => 'lena_hart',
                'display_name' => 'Lena Hart',
                'email' => 'lena@gmail.com',
                'role' => 'user',
                'bio' => 'Enjoys friendly feedback and clear, simple forum structure.',
                'avatar_url' => 'https://api.dicebear.com/9.x/thumbs/svg?seed=Lena%20Hart',
                'is_banned' => false,
                'banned_at' => null,
                'reputation' => 1540,
            ],
            [
                'name' => 'Omar Bell',
                'username' => 'omar_bell',
                'display_name' => 'Omar Bell',
                'email' => 'omar@gmail.com',
                'role' => 'user',
                'bio' => 'Tends to post short comments about what makes a forum easy to use.',
                'avatar_url' => 'https://api.dicebear.com/9.x/thumbs/svg?seed=Omar%20Bell',
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
