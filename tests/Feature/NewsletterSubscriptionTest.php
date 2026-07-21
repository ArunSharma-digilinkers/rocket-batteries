<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Tests\TestCase;

class NewsletterSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscribing_creates_a_subscriber(): void
    {
        Livewire::test('catalog.newsletter-form')
            ->set('email', 'reader@example.com')
            ->call('subscribe')
            ->assertSee('thanks for joining');

        $this->assertDatabaseHas('subscribers', ['email' => 'reader@example.com']);
    }

    public function test_subscribing_with_an_existing_email_does_not_duplicate(): void
    {
        Subscriber::factory()->create(['email' => 'reader@example.com']);

        Livewire::test('catalog.newsletter-form')
            ->set('email', 'reader@example.com')
            ->call('subscribe')
            ->assertSee('thanks for joining');

        $this->assertSame(1, Subscriber::where('email', 'reader@example.com')->count());
    }

    public function test_subscribe_form_honeypot_drops_silently(): void
    {
        Livewire::test('catalog.newsletter-form')
            ->set('email', 'bot@example.com')
            ->set('website', 'http://spam.example')
            ->call('subscribe');

        $this->assertDatabaseMissing('subscribers', ['email' => 'bot@example.com']);
    }

    public function test_subscribe_form_is_rate_limited(): void
    {
        RateLimiter::clear('newsletter:127.0.0.1');

        for ($i = 0; $i < 5; $i++) {
            Livewire::test('catalog.newsletter-form')
                ->set('email', "user{$i}@example.com")
                ->call('subscribe');
        }

        Livewire::test('catalog.newsletter-form')
            ->set('email', 'onetoomany@example.com')
            ->call('subscribe')
            ->assertHasErrors('email');

        $this->assertDatabaseMissing('subscribers', ['email' => 'onetoomany@example.com']);

        RateLimiter::clear('newsletter:127.0.0.1');
    }

    public function test_admin_can_view_subscribers(): void
    {
        $admin = AdminUser::factory()->create();
        $subscriber = Subscriber::factory()->create(['email' => 'reader@example.com']);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.subscribers.index'))
            ->assertOk()
            ->assertSee($subscriber->email);
    }

    public function test_admin_can_remove_a_subscriber(): void
    {
        $admin = AdminUser::factory()->create();
        $subscriber = Subscriber::factory()->create();

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.subscribers.destroy', $subscriber))
            ->assertRedirect(route('admin.subscribers.index'));

        $this->assertDatabaseMissing('subscribers', ['id' => $subscriber->id]);
    }

    public function test_guest_cannot_access_admin_subscribers(): void
    {
        $this->get(route('admin.subscribers.index'))->assertRedirect(route('admin.login'));
    }
}
