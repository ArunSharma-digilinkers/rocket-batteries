<?php

namespace Tests\Feature;

use App\Mail\WarrantyReceived;
use App\Models\AdminUser;
use App\Models\Product;
use App\Models\Series;
use App\Models\Setting;
use App\Models\Warranty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class WarrantySystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::create(['key' => 'contact_email', 'value' => 'admin@example.com', 'group' => 'contact']);
    }

    public function test_registration_form_creates_warranty_and_sends_mail(): void
    {
        Mail::fake();

        $series = Series::factory()->create();
        $product = Product::factory()->create(['series_id' => $series->id]);

        Livewire::test('catalog.warranty-registration-form')
            ->set('customerName', 'Jane Buyer')
            ->set('mobile', '9876543210')
            ->set('productId', $product->id)
            ->set('serialNo', 'SN-TEST-001')
            ->set('purchaseDate', now()->subDays(5)->format('Y-m-d'))
            ->call('submit')
            ->assertSee('pending verification');

        $this->assertDatabaseHas('warranties', [
            'serial_no' => 'SN-TEST-001',
            'mobile' => '9876543210',
            'product_id' => $product->id,
            'status' => 'pending',
        ]);

        Mail::assertSent(WarrantyReceived::class, function ($mail) {
            return $mail->hasTo('admin@example.com') && $mail->warranty->serial_no === 'SN-TEST-001';
        });
    }

    public function test_registration_form_accepts_an_invoice_upload(): void
    {
        Mail::fake();
        Storage::fake('public');

        $series = Series::factory()->create();
        $product = Product::factory()->create(['series_id' => $series->id]);
        $file = UploadedFile::fake()->create('invoice.pdf', 500, 'application/pdf');

        Livewire::test('catalog.warranty-registration-form')
            ->set('customerName', 'Jane Buyer')
            ->set('mobile', '9876543210')
            ->set('productId', $product->id)
            ->set('serialNo', 'SN-TEST-002')
            ->set('purchaseDate', now()->subDays(5)->format('Y-m-d'))
            ->set('invoice', $file)
            ->call('submit');

        $warranty = Warranty::where('serial_no', 'SN-TEST-002')->firstOrFail();
        $this->assertNotNull($warranty->invoice_path);
        Storage::disk('public')->assertExists($warranty->invoice_path);
    }

    public function test_registration_form_rejects_invalid_invoice_type(): void
    {
        Storage::fake('public');

        $series = Series::factory()->create();
        $product = Product::factory()->create(['series_id' => $series->id]);
        $file = UploadedFile::fake()->create('malware.exe', 100, 'application/x-msdownload');

        Livewire::test('catalog.warranty-registration-form')
            ->set('customerName', 'Jane Buyer')
            ->set('mobile', '9876543210')
            ->set('productId', $product->id)
            ->set('serialNo', 'SN-TEST-003')
            ->set('purchaseDate', now()->subDays(5)->format('Y-m-d'))
            ->set('invoice', $file)
            ->call('submit')
            ->assertHasErrors('invoice');

        $this->assertDatabaseMissing('warranties', ['serial_no' => 'SN-TEST-003']);
    }

    public function test_registration_form_honeypot_drops_silently(): void
    {
        Mail::fake();

        $series = Series::factory()->create();
        $product = Product::factory()->create(['series_id' => $series->id]);

        Livewire::test('catalog.warranty-registration-form')
            ->set('customerName', 'Bot')
            ->set('mobile', '0000000000')
            ->set('productId', $product->id)
            ->set('serialNo', 'SN-BOT-001')
            ->set('purchaseDate', now()->format('Y-m-d'))
            ->set('website', 'http://spam.example')
            ->call('submit');

        $this->assertDatabaseMissing('warranties', ['serial_no' => 'SN-BOT-001']);
        Mail::assertNothingSent();
    }

    public function test_registration_form_is_rate_limited(): void
    {
        RateLimiter::clear('warranty:127.0.0.1');

        $series = Series::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $product = Product::factory()->create(['series_id' => $series->id]);

            Livewire::test('catalog.warranty-registration-form')
                ->set('customerName', "User {$i}")
                ->set('mobile', '9876543210')
                ->set('productId', $product->id)
                ->set('serialNo', "SN-RATE-{$i}")
                ->set('purchaseDate', now()->format('Y-m-d'))
                ->call('submit');
        }

        $product = Product::factory()->create(['series_id' => $series->id]);

        Livewire::test('catalog.warranty-registration-form')
            ->set('customerName', 'One Too Many')
            ->set('mobile', '9876543210')
            ->set('productId', $product->id)
            ->set('serialNo', 'SN-RATE-TOOMANY')
            ->set('purchaseDate', now()->format('Y-m-d'))
            ->call('submit')
            ->assertHasErrors('customerName');

        $this->assertDatabaseMissing('warranties', ['serial_no' => 'SN-RATE-TOOMANY']);

        RateLimiter::clear('warranty:127.0.0.1');
    }

    public function test_lookup_finds_a_matching_warranty(): void
    {
        $series = Series::factory()->create();
        $product = Product::factory()->create(['series_id' => $series->id, 'name' => 'RB-EV100']);
        Warranty::factory()->create([
            'product_id' => $product->id,
            'serial_no' => 'SN-LOOKUP-001',
            'mobile' => '9876543210',
            'status' => 'verified',
        ]);

        Livewire::test('catalog.warranty-lookup-form')
            ->set('serialNo', 'SN-LOOKUP-001')
            ->set('mobile', '9876543210')
            ->call('lookup')
            ->assertSee('RB-EV100')
            ->assertSee('Verified');
    }

    public function test_lookup_does_not_leak_data_for_mismatched_mobile(): void
    {
        $series = Series::factory()->create();
        $product = Product::factory()->create(['series_id' => $series->id]);
        Warranty::factory()->create([
            'product_id' => $product->id,
            'serial_no' => 'SN-LOOKUP-002',
            'mobile' => '9876543210',
        ]);

        Livewire::test('catalog.warranty-lookup-form')
            ->set('serialNo', 'SN-LOOKUP-002')
            ->set('mobile', '1111111111')
            ->call('lookup')
            ->assertSee('No warranty found');
    }

    public function test_admin_can_view_and_verify_warranty(): void
    {
        $admin = AdminUser::factory()->create();
        $series = Series::factory()->create();
        $product = Product::factory()->create(['series_id' => $series->id]);
        $warranty = Warranty::factory()->create(['product_id' => $product->id, 'status' => 'pending']);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.warranties.index'))
            ->assertOk()
            ->assertSee($warranty->customer_name);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.warranties.update', $warranty), ['status' => 'verified'])
            ->assertRedirect();

        $warranty->refresh();
        $this->assertSame('verified', $warranty->status);
        $this->assertNotNull($warranty->verified_at);
        $this->assertSame($admin->id, $warranty->verified_by);
    }

    public function test_guest_cannot_access_admin_warranties(): void
    {
        $this->get(route('admin.warranties.index'))->assertRedirect(route('admin.login'));
    }
}
