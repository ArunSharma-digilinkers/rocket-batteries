<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateAdminUserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_admin_user_with_a_hashed_password(): void
    {
        $this->artisan('admin:create', [
            '--name' => 'Site Owner',
            '--email' => 'owner@example.com',
            '--password' => 'a-strong-password',
        ])->assertSuccessful();

        $admin = AdminUser::where('email', 'owner@example.com')->first();

        $this->assertNotNull($admin);
        $this->assertSame('super_admin', $admin->role);
        $this->assertTrue($admin->is_active);
        $this->assertTrue(Hash::check('a-strong-password', $admin->password));
    }

    public function test_it_rejects_a_duplicate_email(): void
    {
        AdminUser::factory()->create(['email' => 'owner@example.com']);

        $this->artisan('admin:create', [
            '--name' => 'Another Owner',
            '--email' => 'owner@example.com',
            '--password' => 'a-strong-password',
        ])->assertFailed();

        $this->assertSame(1, AdminUser::where('email', 'owner@example.com')->count());
    }

    public function test_it_rejects_a_short_password(): void
    {
        $this->artisan('admin:create', [
            '--name' => 'Owner',
            '--email' => 'owner@example.com',
            '--password' => 'short',
        ])->assertFailed();

        $this->assertDatabaseMissing('admin_users', ['email' => 'owner@example.com']);
    }
}
