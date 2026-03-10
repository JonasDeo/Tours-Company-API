<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    // GET /api/admin/quotes
    public function index(Request $request): JsonResponse
    {
        $query = Quote::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('destination')) {
            $query->whereJsonContains('destinations', $request->destination);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%")
                  ->orWhere('email',      'like', "%{$search}%");
            });
        }

        $quotes = $query->paginate($request->get('per_page', 20));

        return response()->json($quotes);
    }

    // GET /api/admin/quotes/{id}
    public function show(Quote $quote): JsonResponse
    {
        return response()->json($quote->load('bookings'));
    }

    // PATCH /api/admin/quotes/{id}
    public function update(Request $request, Quote $quote): JsonResponse
    {
        $data = $request->validate([
            'status'      => 'sometimes|in:PENDING,REVIEWED,RESPONDED,CONVERTED,CLOSED',
            'admin_notes' => 'sometimes|nullable|string|max:2000',
        ]);

        $quote->update($data);

        return response()->json(['message' => 'Quote updated.', 'quote' => $quote]);
    }

    // DELETE /api/admin/quotes/{id}
    public function destroy(Quote $quote): JsonResponse
    {
        $quote->delete();
        return response()->json(['message' => 'Quote deleted.']);
    }

    // GET /api/admin/dashboard/stats
    public function stats(): JsonResponse
    {
        return response()->json([
            'quotes' => [
                'total'     => Quote::count(),
                'pending'   => Quote::where('status', 'PENDING')->count(),
                'converted' => Quote::where('status', 'CONVERTED')->count(),
            ],
        ]);
    }
}