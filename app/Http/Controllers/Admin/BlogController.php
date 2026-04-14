<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // GET /api/admin/blog
    public function index(Request $request): JsonResponse
    {
        $query = BlogPost::latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('published')) {
            $query->where('published', filter_var($request->published, FILTER_VALIDATE_BOOLEAN));
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->paginate(20));
    }

    // POST /api/admin/blog
    public function store(Request $request): JsonResponse
    {
        $post = BlogPost::create($this->validated($request));
        return response()->json($post, 201);
    }

    // GET /api/admin/blog/{id}
    public function show(BlogPost $blogPost): JsonResponse
    {
        return response()->json($blogPost);
    }

    // PUT /api/admin/blog/{id}
    public function update(Request $request, BlogPost $blogPost): JsonResponse
    {
        $blogPost->update($this->validated($request, $blogPost->id));
        return response()->json($blogPost);
    }

    // DELETE /api/admin/blog/{id}
    public function destroy(BlogPost $blogPost): JsonResponse
    {
        $blogPost->delete();
        return response()->json(['message' => 'Post deleted.']);
    }

    // PATCH /api/admin/blog/{id}/publish
    public function togglePublish(BlogPost $blogPost): JsonResponse
    {
        $blogPost->update(['published' => ! $blogPost->published]);
        return response()->json([
            'message'   => $blogPost->published ? 'Post published.' : 'Post unpublished.',
            'published' => $blogPost->published,
        ]);
    }

    // POST /api/admin/blog/{id}/cover
    public function uploadCover(Request $request, BlogPost $blogPost): JsonResponse
    {
        $request->validate(['image' => 'required|image|max:10240']);

        $upload = Cloudinary::uploadApi()->upload(
            $request->file('image')->getRealPath(),
            ['folder' => 'native-kilimanjaro/blog']
        );

        $blogPost->update(['cover_image' => $upload['secure_url']]);

        return response()->json(['url' => $upload['secure_url']]);
    }

    // ── Shared validation ─────────────────────────────────────────────────────

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'nullable|string|unique:blog_posts,slug,' . $ignoreId,
            'category'    => 'required|string|max:100',
            'tags'        => 'nullable|array',
            'tags.*'      => 'string',
            'excerpt'     => 'nullable|string|max:500',
            'content'     => 'required|string',
            'cover_image' => 'nullable|string|url',
            'published'   => 'nullable|boolean',
            'author'      => 'nullable|string|max:100',
            'author_bio'  => 'nullable|string|max:1000',
            'read_time'   => 'nullable|integer|min:1|max:120',
        ]);
    }
}