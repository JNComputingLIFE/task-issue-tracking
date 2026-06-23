@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Projects</h1>
    <a href="{{ route('projects.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-indigo-700 transition">
        + New Project
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <th class="p-4">Project Name</th>
                <th class="p-4">Dates</th>
                <th class="p-4">Open Issues</th>
                <th class="p-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            @forelse($projects as $project)
                <tr class="hover:bg-gray-50/70 transition">
                    <td class="p-4">
                        <a href="{{ route('projects.show', $project) }}" class="font-semibold text-indigo-600 hover:underline block">
                            {{ $project->name }}
                        </a>
                        <span class="text-xs text-gray-400 line-clamp-1">{{ $project->description }}</span>
                    </td>
                    <td class="p-4 text-gray-500 text-xs whitespace-nowrap">
                        @if($project->start_date || $project->deadline)
                            📅 {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : 'Start...' }} 
                            to 
                            {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('M d, Y') : 'End...' }}
                        @else
                            <span class="text-gray-300">No dates set</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                            {{ $project->issues_count }}
                        </span>
                    </td>
                    <td class="p-4 text-right space-x-2 whitespace-nowrap">
                        <a href="{{ route('projects.edit', $project) }}" class="text-gray-500 hover:text-indigo-600 font-medium text-xs">Edit</a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this project?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-400">No projects built yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection