<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BlogPost;
use App\Models\Quote;
use App\Models\Tour;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    // GET /api/admin/dashboard/stats
    public function stats(): JsonResponse
    {
        $totalRevenue = Booking::where('paid', true)->sum('total_amount');

        $conversionRate = Quote::count() > 0
            ? round((Quote::where('status', 'CONVERTED')->count() / Quote::count()) * 100, 1)
            : 0;

        return response()->json([
            'quotes' => [
                'total'     => Quote::count(),
                'pending'   => Quote::where('status', 'PENDING')->count(),
                'responded' => Quote::whereIn('status', ['RESPONDED', 'REVIEWED'])->count(),
                'converted' => Quote::where('status', 'CONVERTED')->count(),
            ],
            'bookings' => [
                'total'     => Booking::count(),
                'confirmed' => Booking::where('status', 'CONFIRMED')->count(),
                'pending'   => Booking::where('status', 'PENDING')->count(),
            ],
            'revenue' => [
                'total_usd' => $totalRevenue,
            ],
            'conversion_rate' => $conversionRate,
            'tours' => [
                'total'     => Tour::count(),
                'published' => Tour::where('published', true)->count(),
            ],
            'blog' => [
                'total'     => BlogPost::count(),
                'published' => BlogPost::where('published', true)->count(),
            ],
            'recent_quotes' => Quote::latest()
                ->limit(5)
                ->get(['id', 'first_name', 'last_name', 'destinations', 'status', 'created_at']),
        ]);
    }
}