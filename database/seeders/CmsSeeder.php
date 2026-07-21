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
            'About Us' => 'Rocket Batteries is a trusted leader in advanced energy solutions, delivering a diverse '
                .'portfolio of industrial, telecom, electric mobility, and automotive batteries across India and '
                .'global markets. Backed by over three decades of expertise, we are committed to driving innovation '
                .'and powering the future of industries, mobility, and infrastructure. Our solutions are designed to '
                .'accelerate the transition to cleaner and more efficient energy systems.',
            'Vision & Mission' => "Our Vision\n\n"
                .'Our vision is to establish Rocket Batteries as a globally recognized and trusted brand by '
                .'consistently delivering high-efficiency, reliable battery solutions across electric vehicles, '
                .'energy storage systems, UPS, solar, and diverse application segments. We are committed to '
                .'strengthening the confidence placed in us by industries and consumers alike, while strategically '
                .'expanding our footprint and presence in international markets.'
                ."\n\nOur Mission\n\n"
                .'At Rocket Batteries, our mission is to deliver reliable and innovative battery solutions that '
                .'address the evolving energy demands of diverse industries. We are committed to designing and '
                .'manufacturing high-quality batteries that offer superior efficiency, durability, and environmental '
                .'responsibility. Through advanced technology and continuous innovation, we aim to support a '
                .'sustainable future by powering applications across mobility, infrastructure, and renewable energy '
                .'ecosystems.',
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

        $testimonials = [
            [
                'name' => 'Demo Customer',
                'designation' => 'Procurement Manager',
                'company' => 'Demo Telecom Pvt. Ltd.',
                'content' => 'Placeholder testimonial content — reliable batteries, great support.',
                'rating' => 5,
                'sort_order' => 1,
            ],
            [
                'name' => 'Demo Fleet Owner',
                'designation' => 'Operations Head',
                'company' => 'Demo Logistics Ltd.',
                'content' => 'Placeholder testimonial content — consistent performance across our EV fleet.',
                'rating' => 5,
                'sort_order' => 2,
            ],
            [
                'name' => 'Demo Facility Manager',
                'designation' => 'Facilities Manager',
                'company' => 'Demo Solar Systems Pvt. Ltd.',
                'content' => 'Placeholder testimonial content — dependable backup power, on-time delivery.',
                'rating' => 4,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                ['name' => $testimonial['name']],
                [
                    'designation' => $testimonial['designation'],
                    'company' => $testimonial['company'],
                    'content' => $testimonial['content'],
                    'rating' => $testimonial['rating'],
                    'sort_order' => $testimonial['sort_order'],
                    'status' => true,
                ]
            );
        }

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

        $newsEvents = [
            [
                'slug' => 'dealer-meet-2023',
                'title' => 'Dealer Meet 2023',
                'content' => 'Happy Valley Park, Bira.',
                'event_date' => '2023-10-07',
            ],
            [
                'slug' => 'ev-expo-2022',
                'title' => '14th Electric Vehicles Expo in India',
                'content' => 'Showcasing our lithium battery manufacturing. Kolkata, 20–24 April 2022.',
                'event_date' => '2022-04-20',
            ],
        ];

        foreach ($newsEvents as $event) {
            NewsEvent::updateOrCreate(
                ['slug' => $event['slug']],
                [
                    'title' => $event['title'],
                    'content' => $event['content'],
                    'event_date' => $event['event_date'],
                    'status' => true,
                ]
            );
        }

        $clients = [
            ['name' => 'Accenture', 'file' => '1.jpg'],
            ['name' => 'Genpact', 'file' => '2.jpg'],
            ['name' => 'Amazon', 'file' => '3.jpg'],
            ['name' => 'ABB', 'file' => '4.jpg'],
            ['name' => 'Schneider Electric', 'file' => '5.jpg'],
            ['name' => 'Fuji Electric', 'file' => '6.jpg'],
            ['name' => 'Hitachi', 'file' => '7.jpg'],
            ['name' => 'Delta', 'file' => '8.jpg'],
            ['name' => 'Vertiv', 'file' => '9.jpg'],
        ];

        foreach ($clients as $i => $client) {
            Client::updateOrCreate(
                ['name' => $client['name']],
                ['logo' => 'img/clients/'.$client['file'], 'sort_order' => $i + 1, 'status' => true]
            );
        }

        $sliders = [
            [
                'title' => 'Engineered Power for Every Electric Vehicle.',
                'subtitle' => 'Rocket Batteries delivers advanced VRLA battery solutions for e-rickshaws, two & '
                    .'three-wheelers, and light commercial EVs — backed by over three decades of manufacturing expertise.',
                'cta_text' => 'Explore EV Batteries',
                'cta_url' => '/products',
                'sort_order' => 1,
            ],
            [
                'title' => 'Unlimited Power, Engineered to Last.',
                'subtitle' => 'From UPS and solar to telecom and industrial backup — a full battery range built for reliability.',
                'cta_text' => 'Browse Full Catalog',
                'cta_url' => '/products',
                'sort_order' => 2,
            ],
            [
                'title' => 'Beyond EV: Our Full Battery Range.',
                'subtitle' => 'ES, ESC, and ESH/TPPL stationary series engineered for demanding backup and standby applications.',
                'cta_text' => 'Explore EnerRocket Series',
                'cta_url' => '/#other-series',
                'sort_order' => 3,
            ],
        ];

        foreach ($sliders as $slide) {
            Slider::updateOrCreate(
                ['title' => $slide['title']],
                [
                    'subtitle' => $slide['subtitle'],
                    'image' => 'placeholder-slide.jpg',
                    'cta_text' => $slide['cta_text'],
                    'cta_url' => $slide['cta_url'],
                    'sort_order' => $slide['sort_order'],
                    'status' => true,
                ]
            );
        }

        $settings = [
            'contact_email' => ['value' => 'info@rocketbatteries.co.in', 'group' => 'contact'],
            'contact_phone' => ['value' => '+91 9967345937', 'group' => 'contact'],
            'contact_address' => ['value' => 'Unit no. 1209, 12th Floor Centrum Business Square, Road No. 16, Near Lotus IT Park, Wagle Estate, Thane West 400602', 'group' => 'contact'],
            'whatsapp_number' => ['value' => '+919967345937', 'group' => 'contact'],
            'social_facebook' => ['value' => '', 'group' => 'social'],
            'social_linkedin' => ['value' => '', 'group' => 'social'],
            'social_instagram' => ['value' => '', 'group' => 'social'],
            'social_youtube' => ['value' => '', 'group' => 'social'],
            'map_embed_url' => [
                'value' => 'https://www.google.com/maps?q='.rawurlencode('Rocket Batteries India Private Limited, Unit no. 1209, 12th Floor Centrum Business Square, Road No. 16, Near Lotus IT Park, Wagle Estate, Thane West 400602').'&output=embed',
                'group' => 'contact',
                'type' => 'textarea',
            ],
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
