<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    // GET /api/admin/settings
    public function index(): JsonResponse
    {
        return response()->json([
            'contact' => Setting::get('contact', [
                'phone'    => '+255 623 880844',
                'whatsapp' => '+255 685 808332',
                'email'    => 'info@balbinasafaris.com',
                'address'  => 'Arusha, Tanzania',
                'website'  => 'https://balbinasafaris.com',
            ]),
            'socials' => Setting::get('socials', [
                'facebook'    => 'https://facebook.com/balbinasafaris',
                'instagram'   => 'https://instagram.com/balbinasafaris',
                'youtube'     => 'https://youtube.com/@balbinasafaris',
                'tripadvisor' => 'https://tripadvisor.com',
            ]),
            'seo' => Setting::get('seo', [
                'metaTitle'       => 'Balbina Safaris — East Africa Safari Tours',
                'metaDescription' => 'Luxury and custom safari tours across Tanzania, Kenya, Uganda, and Zanzibar.',
                'googleAnalytics' => '',
            ]),
        ]);
    }

    // PATCH /api/admin/settings
    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'section' => 'required|in:contact,socials,seo',
            'data'    => 'required|array',
        ]);

        Setting::set($data['section'], $data['data']);

        return response()->json(['message' => 'Settings saved.']);
    }
}