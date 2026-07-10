<?php

namespace Tests\Feature;

use App\Mail\EnquiryReceived;
use App\Models\AdminUser;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\Series;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Tests\TestCase;

class EnquirySystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::create(['key' => 'contact_email', 'value' => 'admin@example.com', 'group' => 'contact']);
    }

    public function test_general_enquiry_form_creates_enquiry_and_sends_mail(): void
    {
        Mail::fake();

        Livewire::test('catalog.general-enquiry-form')
            ->set('name', 'Jane Buyer')
            ->set('email', 'jane@example.com')
            ->set('message', 'Interested in bulk pricing.')
            ->call('submit')
            ->assertSee('Thank you');

        $this->assertDatabaseHas('enquiries', [
            'email' => 'jane@example.com',
            'type' => 'general',
            'product_id' => null,
        ]);

        Mail::assertSent(EnquiryReceived::class, function ($mail) {
            return $mail->hasTo('admin@example.com');
        });
    }

    public function test_general_enquiry_form_honeypot_drops_silently(): void
    {
        Mail::fake();

        Livewire::test('catalog.general-enquiry-form')
            ->set('name', 'Bot')
            ->set('email', 'bot@example.com')
            ->set('message', 'spam')
            ->set('website', 'http://spam.example')
            ->call('submit');

        $this->assertDatabaseMissing('enquiries', ['email' => 'bot@example.com']);
        Mail::assertNothingSent();
    }

    public function test_general_enquiry_form_is_rate_limited(): void
    {
        Mail::fake();
        RateLimiter::clear('enquiry:127.0.0.1');

        for ($i = 0; $i < 5; $i++) {
            Livewire::test('catalog.general-enquiry-form')
                ->set('name', "User {$i}")
                ->set('email', "user{$i}@example.com")
                ->set('message', 'Hello')
                ->call('submit');
        }

        Livewire::test('catalog.general-enquiry-form')
            ->set('name', 'One Too Many')
            ->set('email', 'toomany@example.com')
            ->set('message', 'Hello')
            ->call('submit')
            ->assertHasErrors('name');

        $this->assertDatabaseMissing('enquiries', ['email' => 'toomany@example.com']);

        RateLimiter::clear('enquiry:127.0.0.1');
    }

    public function test_quote_form_sends_mail_with_product_context(): void
    {
        Mail::fake();

        $series = Series::factory()->create();
        $product = Product::factory()->create(['series_id' => $series->id, 'name' => 'RB-EV100']);

        Livewire::test('catalog.request-quote-form', ['productId' => $product->id])
            ->set('name', 'Buyer')
            ->set('email', 'buyer@example.com')
            ->call('submit');

        Mail::assertSent(EnquiryReceived::class, function ($mail) use ($product) {
            return $mail->enquiry->product_id === $product->id;
        });
    }

    public function test_admin_can_view_and_update_enquiry_status(): void
    {
        $admin = AdminUser::factory()->create();
        $enquiry = Enquiry::factory()->create(['status' => 'new']);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.enquiries.index'))
            ->assertOk()
            ->assertSee($enquiry->name);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.enquiries.update', $enquiry), ['status' => 'contacted'])
            ->assertRedirect();

        $this->assertDatabaseHas('enquiries', ['id' => $enquiry->id, 'status' => 'contacted']);
    }

    public function test_guest_cannot_access_admin_enquiries(): void
    {
        $this->get(route('admin.enquiries.index'))->assertRedirect(route('admin.login'));
    }
}
