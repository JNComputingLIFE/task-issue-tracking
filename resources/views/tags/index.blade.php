@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Create Global Tag</h2>
        
        <form action="{{ route('tags.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Tag Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g., Security" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:border-indigo-500 @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Badge Color Accent</label>
                <div class="flex gap-2">
                    <input type="color" name="color" value="{{ old('color', '#6366f1') }}" class="w-12 h-10 border border-gray-300 rounded-lg p-1 cursor-pointer bg-white">
                    <input type="text" placeholder="#6366f1" class="flex-1 border border-gray-300 rounded-lg px-3 text-sm text-gray-400 select-none bg-gray-50" readonly>
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">Save Tag Class</button>
        </form>
    </div>

    <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-800 mb-4">System Tags Ecosystem</h2>
        
        <div class="flex flex-wrap gap-3">
            @forelse($tags as $tag)
                <div class="flex items-center gap-2 border border-gray-100 bg-gray-50/50 pl-3 pr-2 py-1.5 rounded-lg shadow-sm">
                    <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $tag->color ?? '#6366f1' }}"></span>
                    <span class="text-sm font-semibold text-gray-700">{{ $tag->name }}</span>
                    <span class="text-[10px] font-bold bg-white text-gray-400 border border-gray-200 rounded-full px-1.5 py-0.5 ml-1">
                        {{ $tag->issues_count }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-gray-400 py-4 w-full text-center">No metadata system tags instantiated yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection