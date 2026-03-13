<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // GET /api/blog
    public function index(Request $request): JsonResponse
    {
        $query = BlogPost::published()->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Return list without full content for performance
        return response()->json(
            $query->select([
                'id', 'title', 'slug', 'category', 'tags',
                'excerpt', 'cover_image',
                'author', 'author_bio', 'read_time',
                'read_count', 'created_at',
            ])->get()
        );
    }

    // GET /api/blog/{slug}
    public function show(string $slug): JsonResponse
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();
        $post->incrementReads();
        return response()->json($post);
    }
}