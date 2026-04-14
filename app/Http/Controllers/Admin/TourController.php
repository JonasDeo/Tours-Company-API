<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TourController extends Controller
{
    // GET /api/admin/tours
    public function index(Request $request): JsonResponse
    {
        $query = Tour::latest();

        if ($request->filled('destination')) {
            $query->where('destination', $request->destination);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('published')) {
            $query->where('published', filter_var($request->published, FILTER_VALIDATE_BOOLEAN));
        }

        return response()->json($query->paginate(20));
    }

    // POST /api/admin/tours
    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $tour = Tour::create($data);
        return response()->json($tour, 201);
    }

    // GET /api/admin/tours/{id}
    public function show(Tour $tour): JsonResponse
    {
        return response()->json($tour);
    }

    // PUT /api/admin/tours/{id}
    public function update(Request $request, Tour $tour): JsonResponse
    {
        $data = $this->validated($request, $tour->id);
        $tour->update($data);
        return response()->json($tour);
    }

    // DELETE /api/admin/tours/{id}
    public function destroy(Tour $tour): JsonResponse
    {
        $tour->delete();
        return response()->json(['message' => 'Tour deleted.']);
    }

    // PATCH /api/admin/tours/{id}/publish
    public function togglePublish(Tour $tour): JsonResponse
    {
        $tour->update(['published' => ! $tour->published]);
        return response()->json([
            'message'   => $tour->published ? 'Tour published.' : 'Tour unpublished.',
            'published' => $tour->published,
        ]);
    }

    // POST /api/admin/tours/{id}/images
    public function uploadImage(Request $request, Tour $tour): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|max:10240'
        ]);

        $upload = Cloudinary::uploadApi()->upload(
            $request->file('image')->getRealPath(),
            ['folder' => 'native-kilimanjaro/tours']
        );

        $image = [
            'url'       => $upload['secure_url'],
            'public_id' => $upload['public_id'],
        ];

        $images   = $tour->images ?? [];
        $images[] = $image;

        $tour->update(['images' => $images]);

        return response()->json($image);
    }

    // ── Shared validation ─────────────────────────────────────────────────────

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title'              => 'required|string|max:200',
            'slug'               => 'nullable|string|unique:tours,slug,' . $ignoreId,
            'destination'        => 'required|string|max:100',
            'type'               => 'required|in:GUIDED,SELF_DRIVE,MOUNTAIN,BEACH,CAR_RENTAL',
            'duration_days'      => 'required|integer|min:1|max:60',
            'price'              => 'required|integer|min:0',
            'currency'           => 'nullable|string|size:3',
            'excerpt'            => 'nullable|string|max:500',
            'description'        => 'required|string',
            'departure_location' => 'nullable|string|max:200',
            'return_location'    => 'nullable|string|max:200',
            'highlights'         => 'nullable|array',
            'highlights.*'       => 'string|max:300',
            'itinerary'          => 'nullable|array',
            'itinerary.*.day'    => 'required|integer',
            'itinerary.*.title'  => 'required|string|max:300',
            'itinerary.*.desc'   => 'nullable|string',
            'included'           => 'nullable|array',
            'included.*'         => 'string',
            'excluded'           => 'nullable|array',
            'excluded.*'         => 'string',
            'tags'               => 'nullable|array',
            'tags.*'             => 'string|max:100',
            'published'          => 'nullable|boolean',
        ]);
    }
}