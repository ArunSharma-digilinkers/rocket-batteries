<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact', [
            'settings' => [
                'email' => Setting::get('contact_email'),
                'phone' => Setting::get('contact_phone'),
                'address' => Setting::get('contact_address'),
                'whatsapp' => Setting::get('whatsapp_number'),
                'facebook' => Setting::get('social_facebook'),
                'linkedin' => Setting::get('social_linkedin'),
                'instagram' => Setting::get('social_instagram'),
                'youtube' => Setting::get('social_youtube'),
                'mapEmbedUrl' => Setting::get('map_embed_url'),
            ],
        ]);
    }
}
