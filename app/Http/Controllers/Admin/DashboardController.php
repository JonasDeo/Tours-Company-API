<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BlogPost;
use App\Models\Quote;
use App\Models\Tour;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        // ── Core counts ───────────────────────────────────────────────────────

        $quoteTotal     = Quote::count();
        $quotePending   = Quote::where('status', 'PENDING')->count();
        $quoteResponded = Quote::whereIn('status', ['RESPONDED', 'REVIEWED'])->count();
        $quoteConverted = Quote::where('status', 'CONVERTED')->count();
        $quoteClosed    = Quote::where('status', 'CLOSED')->count();

        $bookingTotal     = Booking::count();
        $bookingConfirmed = Booking::where('status', 'CONFIRMED')->count();
        $bookingPending   = Booking::where('status', 'PENDING')->count();

        $totalRevenue     = Booking::where('paid', true)->sum('total_amount');
        $thisMonthRevenue = Booking::where('paid', true)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $conversionRate = $quoteTotal > 0
            ? round(($quoteConverted / $quoteTotal) * 100, 1)
            : 0;

        // ── Pipeline value: sum of pending quote estimated values ─────────────
        // Uses avg booking value as proxy if no explicit amount on quote model.
        // Falls back to null if no bookings exist to base the estimate on.
        $avgBookingValue = $bookingConfirmed > 0
            ? round(Booking::where('status', 'CONFIRMED')->avg('total_amount'), 2)
            : null;

        $pipelineValue = ($avgBookingValue !== null && $quotePending > 0)
            ? round($avgBookingValue * $quotePending, 2)
            : null;

        // ── Avg response time (minutes) ───────────────────────────────────────
        // Measures time between quote created_at and first status change away from PENDING.
        // Requires responded_at column — falls back gracefully if not present.
        $avgResponseMinutes = null;
        try {
            $avg = Quote::whereNotNull('responded_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, responded_at)) as avg_mins')
                ->value('avg_mins');
            $avgResponseMinutes = $avg !== null ? (int) round($avg) : null;
        } catch (\Exception $e) {
            // responded_at column may not exist yet — skip silently
        }

        // ── Top destination ───────────────────────────────────────────────────
        $topDestination = null;
        try {
            $top = Booking::where('status', 'CONFIRMED')
                ->whereNotNull('destination')
                ->select('destination', DB::raw('COUNT(*) as bookings'))
                ->groupBy('destination')
                ->orderByDesc('bookings')
                ->first();
            if ($top) {
                $topDestination = [
                    'name'     => $top->destination,
                    'bookings' => $top->bookings,
                ];
            }
        } catch (\Exception $e) {
            // destination column may not exist — skip silently
        }

        // ── Bookings trend: last 30 days 
        $trend = Booking::where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($row) => [
                'date'  => $row->date,
                'count' => (int) $row->count,
            ])
            ->toArray();

        // ── Recent quotes 
        $recentQuotes = Quote::latest()
            ->limit(5)
            ->get(['id', 'first_name', 'last_name', 'destinations', 'status', 'created_at']);

        // ── Response 
        return response()->json([
            'quotes' => [
                'total'     => $quoteTotal,
                'pending'   => $quotePending,
                'responded' => $quoteResponded,
                'converted' => $quoteConverted,
                'closed'    => $quoteClosed,
            ],
            'bookings' => [
                'total'     => $bookingTotal,
                'confirmed' => $bookingConfirmed,
                'pending'   => $bookingPending,
                'trend'     => $trend,
            ],
            'revenue' => [
                'total_usd'       => $totalRevenue,
                'this_month_usd'  => $thisMonthRevenue,
            ],
            'conversion_rate'       => $conversionRate,
            'pipeline_value_usd'    => $pipelineValue,
            'avg_response_minutes'  => $avgResponseMinutes,
            'avg_booking_value_usd' => $avgBookingValue,
            'top_destination'       => $topDestination,
            'tours' => [
                'total'     => Tour::count(),
                'published' => Tour::where('published', true)->count(),
            ],
            'blog' => [
                'total'     => BlogPost::count(),
                'published' => BlogPost::where('published', true)->count(),
            ],
            'recent_quotes' => $recentQuotes,
        ]);
    }
}