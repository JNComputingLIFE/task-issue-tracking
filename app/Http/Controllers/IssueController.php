<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use App\Http\Requests\StoreIssueRequest;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function index(Request $request)
    {
        // --- BONUS: Eager loaded 'users' relationship ---
        $query = Issue::with(['project', 'tags', 'users']);

        // --- BONUS: Debounced Text Search Implementation ---
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }

        $issues = $query->latest()->paginate(10)->withQueryString();
        $tags = Tag::all();
        
        // --- BONUS: If AJAX search request, return specific list rows directly ---
        if ($request->ajax()) {
            return view('issues.partials.list_rows', compact('issues'))->render();
        }

        return view('issues.index', compact('issues', 'tags'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('issues.create', compact('projects'));
    }

    public function store(StoreIssueRequest $request)
    {
        Issue::create($request->validated());
        return redirect()->route('issues.index')->with('success', 'Issue created successfully.');
    }

    public function show(Issue $issue)
    {
        // --- BONUS: Eager loaded 'users' relationship ---
        $issue->load(['project', 'tags', 'users']);
        $allTags = Tag::all();
        
        // --- BONUS: Load user directory to fill selection views ---
        $allUsers = User::all();

        return view('issues.show', compact('issue', 'allTags', 'allUsers'));
    }

    public function edit(Issue $issue)
    {
        $projects = Project::all();
        return view('issues.edit', compact('issue', 'projects'));
    }

    public function update(StoreIssueRequest $request, Issue $issue)
    {
        $issue->update($request->validated());
        return redirect()->route('issues.index')->with('success', 'Issue updated successfully.');
    }

    public function destroy(Issue $issue)
    {
        $issue->delete();
        return redirect()->route('issues.index')->with('success', 'Issue deleted successfully.');
    }

    public function attachTag(Issue $issue, Tag $tag)
    {
        $issue->tags()->syncWithoutDetaching([$tag->id]);
        return response()->json(['success' => true, 'tag' => $tag]);
    }

    public function detachTag(Issue $issue, Tag $tag)
    {
        $issue->tags()->detach($tag->id);
        return response()->json(['success' => true]);
    }

    // --- BONUS: Handle AJAX user attachments and detachments ---
    public function attachUser(Issue $issue, User $user)
    {
        $issue->users()->syncWithoutDetaching([$user->id]);
        return response()->json(['success' => true, 'user' => $user]);
    }

    public function detachUser(Issue $issue, User $user)
    {
        $issue->users()->detach($user->id);
        return response()->json(['success' => true]);
    }
}