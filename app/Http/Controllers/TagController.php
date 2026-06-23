<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('issues')->get();
        return view('tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:tags,name|max:255',
            'color' => 'nullable|string|max:7'
        ]);

        Tag::create($validated);
        return redirect()->route('tags.index')->with('success', 'Tag created successfully.');
    }
}