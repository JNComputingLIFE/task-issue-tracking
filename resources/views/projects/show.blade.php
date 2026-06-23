@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('projects.index') }}" class="text-xs font-semibold text-gray-500 hover:text-indigo-600 transition">← Back to Projects</a>
    <h1 class="text-3xl font-bold text-gray-800 mt-2">{{ $project->name }}</h1>
    <p class="text-gray-600 text-sm mt-1 max-w-2xl">{{ $project->description ?? 'No description provided.' }}</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Project Issues</h2>
    
    <div class="space-y-3">
        @forelse($project->issues as $issue)
            <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-gray-50/50 transition">
                <div>
                    <a href="{{ route('issues.show', $issue) }}" class="font-semibold text-gray-800 hover:text-indigo-600 transition block text-sm">
                        {{ $issue->title }}
                    </a>
                    <div class="flex gap-1 mt-1.5">
                        @foreach($issue->tags as $tag)
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium text-white" style="background-color: {{ $tag->color ?? '#6366f1' }}">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="px-2 py-0.5 rounded text-xs font-semibold uppercase {{ $issue->priority === 'high' ? 'bg-red-50 text-red-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $issue->priority }}
                    </span>
                    <span class="text-xs font-medium text-gray-400 uppercase">
                        {{ str_replace('_', ' ', $issue->status) }}
                    </span>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400 text-center py-6">No current issues logged against this project.</p>
        @endforelse
    </div>
</div>
@endsection