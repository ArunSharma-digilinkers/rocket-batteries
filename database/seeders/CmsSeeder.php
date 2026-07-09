<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Models\NewsEvent;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CmsSeeder extends Seeder
{
    /**
     * Demo CMS content — placeholder copy/images for local development.
     */
    public function run(): void
    {
        $pages = [
            'About Us' => 'Placeholder about-us copy. Replace with real client content before launch.',
            'Vision & Mission' => 'Placeholder vision & mission statement.',
        ];

        foreach ($pages as $title => $content) {
            Page::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'content' => $content,
                    'meta_title' => "{$title} | Rocket Batteries",
                    'status' => true,
                ]
            );
        }

        Testimonial::updateOrCreate(
            ['name' => 'Demo Customer'],
            [
                'designation' => 'Procurement Manager',
                'company' => 'Demo Telecom Pvt. Ltd.',
                'content' => 'Placeholder testimonial content — reliable batteries, great support.',
                'rating' => 5,
                'sort_order' => 1,
                'status' => true,
            ]
        );

        $album = GalleryAlbum::updateOrCreate(
            ['slug' => 'factory-tour'],
            [
                'title' => 'Factory Tour',
                'description' => 'Placeholder gallery album.',
                'sort_order' => 1,
                'status' => true,
            ]
        );

        GalleryImage::updateOrCreate(
            ['gallery_album_id' => $album->id, 'caption' => 'Production line'],
            ['image' => 'placeholder.jpg', 'sort_order' => 1]
        );

        NewsEvent::updateOrCreate(
            ['slug' => 'demo-trade-show'],
            [
                'title' => 'Demo Trade Show Participation',
                'content' => 'Placeholder news/event entry.',
                'event_date' => now()->addMonth(),
                'status' => true,
            ]
        );

        Client::updateOrCreate(
            ['name' => 'Demo Client Ltd.'],
            ['logo' => 'placeholder-logo.png', 'sort_order' => 1, 'status' => true]
        );

        Slider::updateOrCreate(
            ['title' => 'Reliable Power, Built to Last'],
            [
                'subtitle' => 'Industrial batteries for UPS, solar, telecom and EV applications.',
                'image' => 'placeholder-slide.jpg',
                'cta_text' => 'View Catalog',
                'cta_url' => '/',
                'sort_order' => 1,
                'status' => true,
            ]
        );

        $settings = [
            'contact_email' => ['value' => 'info@rocketbatteries.test', 'group' => 'contact'],
            'contact_phone' => ['value' => '+91 00000 00000', 'group' => 'contact'],
            'contact_address' => ['value' => 'Placeholder address, India', 'group' => 'contact'],
            'whatsapp_number' => ['value' => '+910000000000', 'group' => 'contact'],
            'social_facebook' => ['value' => '', 'group' => 'social'],
            'social_linkedin' => ['value' => '', 'group' => 'social'],
            'social_instagram' => ['value' => '', 'group' => 'social'],
            'map_embed_url' => ['value' => '', 'group' => 'contact', 'type' => 'textarea'],
        ];

        foreach ($settings as $key => $data) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $data['value'],
                    'group' => $data['group'],
                    'type' => $data['type'] ?? 'text',
                ]
            );
        }
    }
}
