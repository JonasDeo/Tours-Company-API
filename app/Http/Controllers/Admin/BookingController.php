<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // GET /api/admin/bookings
    public function index(Request $request): JsonResponse
    {
        $query = Booking::with(['quote', 'tour'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('paid')) {
            $query->where('paid', filter_var($request->paid, FILTER_VALIDATE_BOOLEAN));
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('client_name',  'like', "%{$search}%")
                  ->orWhere('client_email', 'like', "%{$search}%");
            });
        }

        return response()->json($query->paginate(20));
    }

    // GET /api/admin/bookings/{id}
    public function show(Booking $booking): JsonResponse
    {
        return response()->json($booking->load(['quote', 'tour']));
    }

    // POST /api/admin/bookings
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'quote_id'      => 'nullable|exists:quotes,id',
            'tour_id'       => 'nullable|exists:tours,id',
            'client_name'   => 'required|string|max:200',
            'client_email'  => 'required|email',
            'client_phone'  => 'nullable|string|max:30',
            'arrival_date'  => 'nullable|date',
            'adults'        => 'required|integer|min:1',
            'children'      => 'nullable|integer|min:0',
            'total_amount'  => 'required|integer|min:0',
            'currency'      => 'nullable|string|size:3',
            'notes'         => 'nullable|string',
        ]);

        $booking = Booking::create($data);
        return response()->json($booking, 201);
    }

    // PATCH /api/admin/bookings/{id}
    public function update(Request $request, Booking $booking): JsonResponse
    {
        $data = $request->validate([
            'status'       => 'sometimes|in:PENDING,CONFIRMED,COMPLETED,CANCELLED',
            'paid'         => 'sometimes|boolean',
            'total_amount' => 'sometimes|integer|min:0',
            'notes'        => 'sometimes|nullable|string',
        ]);

        if (isset($data['paid']) && $data['paid'] && ! $booking->paid) {
            $data['paid_at'] = now();
        }

        $booking->update($data);
        return response()->json($booking);
    }

    // DELETE /api/admin/bookings/{id}
    public function destroy(Booking $booking): JsonResponse
    {
        $booking->delete();
        return response()->json(['message' => 'Booking deleted.']);
    }
}