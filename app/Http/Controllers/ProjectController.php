<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Issue;

class ProjectController extends Controller
{
 public function index()
{
    // Eager load the 'owner' relationship along with the issues count
    $projects = Project::with(['owner'])->withCount('issues')->latest()->get();
    
    return view('projects.index', compact('projects'));
}

    public function create()
    {
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        
        // Capture authenticated ID, fallback to first user for guest/testing states
        $data['user_id'] = auth()->id() ?? \App\Models\User::first()?->id;

        Project::create($data);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $project->load(['issues' => function ($query) {
            $query->with(['tags', 'users'])->latest(); // Eager loaded bonus user relations here
        }]);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        // --- BONUS: Authorization Policy Control ---
        Gate::authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(StoreProjectRequest $request, Project $project)
    {
        // --- BONUS: Authorization Policy Control ---
        Gate::authorize('update', $project);

        $project->update($request->validated());
        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        // --- BONUS: Authorization Policy Control ---
        Gate::authorize('delete', $project);

        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}