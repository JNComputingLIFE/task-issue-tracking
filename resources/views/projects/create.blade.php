@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <h1 class="text-xl font-bold mb-6 text-gray-800">Create New Project</h1>

    <form action="{{ route('projects.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Project Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Deadline</label>
                <input type="date" name="deadline" value="{{ old('deadline') }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                @error('deadline') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('projects.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition">Save Project</button>
        </div>
    </form>
</div>
@endsection