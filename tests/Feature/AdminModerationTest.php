<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminModerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_any_thread(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $author = User::factory()->create();
        $category = Category::create([
            'name' => 'General',
            'slug' => 'general',
            'description' => 'General topics',
            'thread_count' => 1,
        ]);
        $thread = Thread::create([
            'category_id' => $category->id,
            'user_id' => $author->id,
            'title' => 'A thread owned by another user',
            'slug' => Str::slug('A thread owned by another user'),
            'body' => 'Body content long enough for tests.',
            'last_activity_at' => now(),
        ]);

        $response = $this->actingAs($admin)->delete(route('threads.destroy', $thread));

        $response->assertRedirect(route('home'));
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
    }

    public function test_non_admin_non_owner_cannot_delete_thread(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::create([
            'name' => 'General',
            'slug' => 'general',
            'description' => 'General topics',
            'thread_count' => 1,
        ]);
        $thread = Thread::create([
            'category_id' => $category->id,
            'user_id' => $author->id,
            'title' => 'A protected thread title',
            'slug' => Str::slug('A protected thread title'),
            'body' => 'Body content long enough for tests.',
            'last_activity_at' => now(),
        ]);

        $response = $this->actingAs($otherUser)->delete(route('threads.destroy', $thread));

        $response->assertForbidden();
        $this->assertDatabaseHas('threads', ['id' => $thread->id]);
    }

    public function test_admin_can_toggle_member_ban_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['is_banned' => false, 'banned_at' => null]);

        $this->actingAs($admin)->patch(route('members.ban.toggle', $member->username))
            ->assertRedirect();

        $member->refresh();
        $this->assertTrue($member->is_banned);
        $this->assertNotNull($member->banned_at);

        $this->actingAs($admin)->patch(route('members.ban.toggle', $member->username))
            ->assertRedirect();

        $member->refresh();
        $this->assertFalse($member->is_banned);
        $this->assertNull($member->banned_at);
    }

    public function test_non_admin_cannot_toggle_member_ban_status(): void
    {
        $member = User::factory()->create();
        $nonAdmin = User::factory()->create();

        $response = $this->actingAs($nonAdmin)->patch(route('members.ban.toggle', $member->username));

        $response->assertForbidden();
    }
}
