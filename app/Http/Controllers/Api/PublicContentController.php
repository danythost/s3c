<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;

class PublicContentController extends Controller
{
    /**
     * Get active products for the shop.
     */
    public function products()
    {
        $products = Product::where('status', 'active')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get page content by slug.
     */
    public function page($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $page
        ]);
    }

    /**
     * Get public site settings.
     * Only exposes non-sensitive data.
     */
    public function settings()
    {
        $safeGroups = ['general', 'branding', 'contact', 'social'];
        
        $settings = Setting::whereIn('group', $safeGroups)
            ->get()
            ->pluck('value', 'key');

        // Add some defaults if not found in DB
        $defaults = [
            'site_name' => config('app.name', 'S3C'),
            'contact_email' => 'support@s3c.com.ng',
            'currency' => '₦',
            'wallet_funding_charge' => Setting::getValue('wallet_funding_charge', 30),
        ];

        return response()->json([
            'success' => true,
            'data' => array_merge($defaults, $settings->toArray())
        ]);
    }

    /**
     * Get active announcements/broadcasts.
     */
    public function announcements()
    {
        $now = now();
        $announcements = \App\Models\Announcement::where('is_active', true)
            ->where(function ($query) use ($now) {
                $query->whereNull('start_at')
                    ->orWhere('start_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('end_at')
                    ->orWhere('end_at', '>=', $now);
            })
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $announcements
        ]);
    }
}
