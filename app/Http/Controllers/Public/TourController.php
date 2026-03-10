<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TourController extends Controller
{
    // GET /api/tours
    public function index(Request $request): JsonResponse
    {
        $query = Tour::published()->latest();

        if ($request->filled('destination')) {
            $query->where('destination', $request->destination);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return response()->json($query->get());
    }

    // GET /api/tours/{slug}
    public function show(string $slug): JsonResponse
    {
        $tour = Tour::published()->where('slug', $slug)->firstOrFail();
        return response()->json($tour);
    }
}