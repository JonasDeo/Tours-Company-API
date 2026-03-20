<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    public function index(): JsonResponse
    {
        $testimonials = Testimonial::where('featured', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json($testimonials);
    }
}