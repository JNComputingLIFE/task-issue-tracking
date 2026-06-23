<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\User;
class CommentController extends Controller
{
    public function index(Issue $issue)
    {
        $comments = $issue->comments()->latest()->paginate(5);
        return response()->json($comments);
    }

    public function store(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $comment = $issue->comments()->create($validated);
        return response()->json($comment, 201);
    }
}