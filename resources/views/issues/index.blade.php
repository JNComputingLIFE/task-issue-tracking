@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">All Issues</h1>
        <p class="text-sm text-gray-500">Filter, search, and track cross-project work item status.</p>
    </div>
    <a href="{{ route('issues.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-indigo-700 transition whitespace-nowrap">
        + New Issue
    </a>
</div>

<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
    <form action="{{ route('issues.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Status</label>
            <select name="status" class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white">
                <option value="">All Statuses</option>
                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Priority</label>
            <select name="priority" class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white">
                <option value="">All Priorities</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Tag</label>
            <select name="tag" class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white">
                <option value="">All Tags</option>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-gray-800 hover:bg-gray-900 text-white font-semibold text-sm py-2 rounded-lg transition">Filter</button>
            <a href="{{ route('issues.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-center font-semibold text-sm py-2 px-3 rounded-lg transition">Reset</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <th class="p-4">Issue Details</th>
                <th class="p-4">Project</th>
                <th class="p-4">Status & Priority</th>
                <th class="p-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            @forelse($issues as $issue)
                <tr class="hover:bg-gray-50/70 transition">
                    <td class="p-4">
                        <a href="{{ route('issues.show', $issue) }}" class="font-semibold text-gray-900 hover:text-indigo-600 transition block">
                            {{ $issue->title }}
                        </a>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($issue->tags as $t)
                                <span class="px-2 py-0.5 rounded text-[10px] font-medium text-white" style="background-color: {{ $t->color ?? '#6366f1' }}">
                                    {{ $t->name }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="p-4 text-gray-500 font-medium">
                        {{ $issue->project->name }}
                    </td>
                    <td class="p-4 space-x-1 whitespace-nowrap">
                        <span class="px-2 py-0.5 rounded text-xs font-bold uppercase {{ $issue->status === 'closed' ? 'bg-gray-100 text-gray-800' : 'bg-blue-50 text-blue-700' }}">
                            {{ str_replace('_', ' ', $issue->status) }}
                        </span>
                        <span class="px-2 py-0.5 rounded text-xs font-bold uppercase {{ $issue->priority === 'high' ? 'bg-red-50 text-red-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $issue->priority }}
                        </span>
                    </td>
                    <td class="p-4 text-right space-x-2 whitespace-nowrap">
                        <a href="{{ route('issues.edit', $issue) }}" class="text-gray-500 hover:text-indigo-600 font-medium text-xs">Edit</a>
                        <form action="{{ route('issues.destroy', $issue) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this issue permanently?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-400">No matching issues discovered.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $issues->links() }}
</div>
@endsection