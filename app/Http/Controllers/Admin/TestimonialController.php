<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    // GET /api/admin/testimonials
    public function index(): JsonResponse
    {
        return response()->json(Testimonial::orderBy('sort_order')->orderBy('id')->get());
    }

    // POST /api/admin/testimonials
    public function store(Request $request): JsonResponse
    {
        $testimonial = Testimonial::create($this->validated($request));
        return response()->json($testimonial, 201);
    }

    // PUT /api/admin/testimonials/{testimonial}
    public function update(Request $request, Testimonial $testimonial): JsonResponse
    {
        $testimonial->update($this->validated($request));
        return response()->json($testimonial);
    }

    // DELETE /api/admin/testimonials/{testimonial}
    public function destroy(Testimonial $testimonial): JsonResponse
    {
        $testimonial->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    // POST /api/admin/testimonials/{testimonial}/avatar
    public function uploadAvatar(Request $request, Testimonial $testimonial): JsonResponse
    {
        $request->validate(['image' => 'required|image|max:5120']);

        $upload = Cloudinary::uploadApi()->upload(
            $request->file('image')->getRealPath(),
            ['folder' => 'balbina/testimonials', 'transformation' => ['width' => 200, 'height' => 200, 'crop' => 'fill', 'gravity' => 'face']]
        );

        $testimonial->update(['avatar' => $upload['secure_url']]);
        return response()->json(['url' => $upload['secure_url']]);
    }

    // PATCH /api/admin/testimonials/reorder
    public function reorder(Request $request): JsonResponse
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);
        foreach ($request->order as $position => $id) {
            Testimonial::where('id', $id)->update(['sort_order' => $position]);
        }
        return response()->json(['message' => 'Reordered.']);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'       => 'required|string|max:100',
            'location'   => 'nullable|string|max:100',
            'text'       => 'required|string|max:1000',
            'rating'     => 'nullable|integer|min:1|max:5',
            'featured'   => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);
    }
}