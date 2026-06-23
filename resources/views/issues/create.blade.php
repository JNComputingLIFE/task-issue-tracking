@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <h1 class="text-xl font-bold mb-6 text-gray-800">Log New Issue</h1>

    <form action="{{ route('issues.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Target Project</label>
            <select name="project_id" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm bg-white @error('project_id') border-red-500 @enderror">
                <option value="">Select a project...</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                @endforeach
            </select>
            @error('project_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Issue Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:border-indigo-500 @error('title') border-red-500 @enderror">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Detailed Description</label>
            <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:border-indigo-500">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm bg-white">
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Priority</label>
                <select name="priority" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm bg-white">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm">
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('issues.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">Create Issue</button>
        </div>
    </form>
</div>
@endsection