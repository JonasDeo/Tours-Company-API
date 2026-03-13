<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // GET /api/blog/{slug}/comments
    public function index(BlogPost $blogPost): JsonResponse
    {
        $comments = $blogPost->comments()
            ->where('approved', true)
            ->latest()
            ->get(['id', 'name', 'body', 'created_at']);

        return response()->json($comments);
    }

    // POST /api/blog/{slug}/comments
    public function store(Request $request, BlogPost $blogPost): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'body' => 'required|string|max:2000',
        ]);

        $comment = $blogPost->comments()->create([
            'name'     => $data['name'],
            'body'     => $data['body'],
            'approved' => false, // requires admin approval
        ]);

        return response()->json([
            'id'         => $comment->id,
            'name'       => $comment->name,
            'body'       => $comment->body,
            'created_at' => $comment->created_at,
            'message'    => 'Your comment has been submitted and is awaiting approval.',
        ], 201);
    }
}