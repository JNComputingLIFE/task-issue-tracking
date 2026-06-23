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
    <form id="filter-form" action="{{ route('issues.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-5 gap-4 items-end">
        <div class="sm:col-span-2">
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Search Issues</label>
            <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="Search title or description..." class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white focus:outline-none focus:border-indigo-500">
        </div>

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
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <th class="p-4">Issue Details</th>
                <th class="p-4">Project</th>
                <th class="p-4">Assignees</th>
                <th class="p-4">Status & Priority</th>
                <th class="p-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody id="issues-table-body" class="divide-y divide-gray-100 text-sm">
            @include('issues.partials.list_rows', ['issues' => $issues])
        </tbody>
    </table>
</div>

<div id="pagination-wrapper" class="mt-4">
    {{ $issues->links() }}
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const filterForm = document.getElementById('filter-form');
    let debounceTimeout = null;

    function triggerSearch() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData).toString();

        fetch(`{{ route('issues.index') }}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('issues-table-body').innerHTML = html;
            // Note: If you want pagination links to update dynamically over AJAX search too,
            // you can include pagination elements inside the partial view template payload.
        })
        .catch(err => console.error('Error fetching search results:', err));
    }

    // Debounce watcher structure running at 350ms delays
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(triggerSearch, 350);
    });

    // Run dynamic structural update when filters shift directly
    filterForm.querySelectorAll('select').forEach(elem => {
        elem.addEventListener('change', triggerSearch);
    });
});
</script>
@endsection