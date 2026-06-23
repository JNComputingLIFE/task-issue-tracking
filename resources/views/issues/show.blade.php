@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8" id="issue-container" data-issue-id="{{ $issue->id }}">
    
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-indigo-600 font-semibold tracking-wider uppercase">{{ $issue->project->name }}</span>
                <div class="space-x-2">
                    <span class="px-2.5 py-1 text-xs font-bold rounded-full uppercase {{ $issue->status === 'closed' ? 'bg-gray-100 text-gray-800' : ($issue->status === 'in_progress' ? 'bg-blue-50 text-blue-700' : 'bg-green-50 text-green-700') }}">
                        {{ str_replace('_', ' ', $issue->status) }}
                    </span>
                    <span class="px-2.5 py-1 text-xs font-bold rounded-full uppercase {{ $issue->priority === 'high' ? 'bg-red-50 text-red-700' : ($issue->priority === 'medium' ? 'bg-yellow-50 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                        {{ $issue->priority }} Priority
                    </span>
                </div>
            </div>
            <h1 class="text-2xl font-bold mb-4">{{ $issue->title }}</h1>
            <p class="text-gray-600 whitespace-pre-wrap">{{ $issue->description ?? 'No description provided.' }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-6">
            <h2 class="text-lg font-bold">Comments</h2>
            
            <div id="comment-errors" class="hidden bg-red-50 border-l-4 border-red-500 p-4 rounded text-red-700 text-sm"></div>

            <form id="comment-form" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <input type="text" id="comment-author" placeholder="Your Name" class="sm:col-span-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
                    <input type="text" id="comment-body" placeholder="Add a public comment..." class="sm:col-span-3 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">Comment</button>
            </form>

            <div id="comments-wrapper" class="divide-y divide-gray-100 space-y-4 pt-2"></div>
            
            <button id="load-more-comments" class="hidden text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition py-2 w-full text-center">
                Load older comments
            </button>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-700">Tags</h3>
                <button onclick="toggleTagModal(true)" class="text-xs font-semibold text-indigo-600 hover:underline">Manage</button>
            </div>
            <div id="active-tags" class="flex flex-wrap gap-1.5">
                @foreach($issue->tags as $tag)
                    <span data-tag-id="{{ $tag->id }}" class="px-2.5 py-1 rounded text-xs font-medium text-white shadow-sm" style="background-color: {{ $tag->color ?? '#6366f1' }}">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-700">Assignees</h3>
                <button onclick="toggleUserModal(true)" class="text-xs font-semibold text-indigo-600 hover:underline">Assign</button>
            </div>
            <div id="active-users" class="flex flex-wrap gap-1.5">
                @foreach($issue->users as $user)
                    <span data-user-id="{{ $user->id }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200 shadow-sm">
                        {{ $user->name }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div id="tag-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50 animate-fade-in">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 relative">
        <h3 class="text-lg font-bold mb-4">Toggle Assignment Tags</h3>
        <div class="max-h-64 overflow-y-auto space-y-2 mb-6">
            @foreach($allTags as $tag)
                <label class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg cursor-pointer border border-gray-100">
                    <span class="text-sm font-medium flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full" style="background-color: {{ $tag->color ?? '#6366f1' }}"></span>
                        {{ $tag->name }}
                    </span>
                    <input type="checkbox" 
                           class="tag-checkbox h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" 
                           data-tag-id="{{ $tag->id }}"
                           {{ $issue->tags->contains($tag->id) ? 'checked' : '' }}>
                </label>
            @endforeach
        </div>
        <button onclick="toggleTagModal(false)" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-2.5 rounded-lg font-semibold text-sm transition">Close Panel</button>
    </div>
</div>

<div id="user-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50 animate-fade-in">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 relative">
        <h3 class="text-lg font-bold mb-4">Manage Assigned Members</h3>
        <div class="max-h-64 overflow-y-auto space-y-2 mb-6">
            @foreach($allUsers as $user)
                <label class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg cursor-pointer border border-gray-100">
                    <span class="text-sm font-medium flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold flex items-center justify-center">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                        {{ $user->name }}
                    </span>
                    <input type="checkbox" 
                           class="user-checkbox h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" 
                           data-user-id="{{ $user->id }}"
                           {{ $issue->users->contains($user->id) ? 'checked' : '' }}>
                </label>
            @endforeach
        </div>
        <button onclick="toggleUserModal(false)" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-2.5 rounded-lg font-semibold text-sm transition">Close Panel</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const issueId = document.getElementById('issue-container').dataset.issueId;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    let nextCommentPageUrl = `/issues/${issueId}/comments`;

    // Headers Utility Helper
    const secureHeaders = () => ({
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    });

    // --- COMMENTS FETCH ENGINE ---
    async function fetchComments(append = false) {
        if (!nextCommentPageUrl) return;
        try {
            const res = await fetch(nextCommentPageUrl, { headers: secureHeaders() });
            const data = await res.json();
            
            const wrapper = document.getElementById('comments-wrapper');
            if (!append) wrapper.innerHTML = '';

            data.data.forEach(comment => {
                wrapper.appendChild(renderCommentNode(comment));
            });

            nextCommentPageUrl = data.next_page_url;
            document.getElementById('load-more-comments').classList.toggle('hidden', !nextCommentPageUrl);
        } catch (e) { console.error("Error structuralizing engine load", e); }
    }

    function renderCommentNode(comment) {
        const div = document.createElement('div');
        div.className = 'pt-4 border-t border-gray-100 first:border-0 first:pt-0 animate-fade-in';
        div.innerHTML = `
            <div class="flex justify-between items-center text-xs text-gray-400 mb-1">
                <span class="font-semibold text-gray-700">${escapeHtml(comment.author_name)}</span>
                <span>${new Date(comment.created_at).toLocaleDateString()}</span>
            </div>
            <p class="text-sm text-gray-600">${escapeHtml(comment.body)}</p>
        `;
        return div;
    }

    // --- SUBMIT COMMENT ENGINE ---
    document.getElementById('comment-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const errBox = document.getElementById('comment-errors');
        errBox.classList.add('hidden');

        const payload = {
            author_name: document.getElementById('comment-author').value,
            body: document.getElementById('comment-body').value
        };

        try {
            const res = await fetch(`/issues/${issueId}/comments`, {
                method: 'POST',
                headers: secureHeaders(),
                body: JSON.stringify(payload)
            });

            if (res.status === 422) {
                const errors = await res.json();
                errBox.innerHTML = Object.values(errors.errors).flat().join('<br>');
                errBox.classList.remove('hidden');
                return;
            }

            const comment = await res.json();
            document.getElementById('comments-wrapper').prepend(renderCommentNode(comment));
            document.getElementById('comment-form').reset();
        } catch (e) { console.error(e); }
    });

    // --- INTERACTIVE TAG OPERATIONS ---
    document.querySelectorAll('.tag-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', async function() {
            const tagId = this.dataset.tagId;
            const activeTagsContainer = document.getElementById('active-tags');

            if (this.checked) {
                const res = await fetch(`/issues/${issueId}/tags/${tagId}`, { method: 'POST', headers: secureHeaders() });
                const data = await res.json();
                if(data.success) {
                    const span = document.createElement('span');
                    span.dataset.tagId = data.tag.id;
                    span.className = 'px-2.5 py-1 rounded text-xs font-medium text-white shadow-sm';
                    span.style.backgroundColor = data.tag.color || '#6366f1';
                    span.innerText = data.tag.name;
                    activeTagsContainer.appendChild(span);
                }
            } else {
                const res = await fetch(`/issues/${issueId}/tags/${tagId}`, { method: 'DELETE', headers: secureHeaders() });
                const data = await res.json();
                if(data.success) {
                    const targetSpan = activeTagsContainer.querySelector(`[data-tag-id="${tagId}"]`);
                    if(targetSpan) targetSpan.remove();
                }
            }
        });
    });

    // --- BONUS: INTERACTIVE MEMBER ASSIGNMENT OPERATIONS ---
    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', async function() {
            const userId = this.dataset.userId;
            const activeUsersContainer = document.getElementById('active-users');

            if (this.checked) {
                const res = await fetch(`/issues/${issueId}/users/${userId}`, { method: 'POST', headers: secureHeaders() });
                const data = await res.json();
                if(data.success) {
                    const span = document.createElement('span');
                    span.dataset.userId = data.user.id;
                    span.className = 'inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200 shadow-sm';
                    span.innerText = data.user.name;
                    activeUsersContainer.appendChild(span);
                }
            } else {
                const res = await fetch(`/issues/${issueId}/users/${userId}`, { method: 'DELETE', headers: secureHeaders() });
                const data = await res.json();
                if(data.success) {
                    const targetSpan = activeUsersContainer.querySelector(`[data-user-id="${userId}"]`);
                    if(targetSpan) targetSpan.remove();
                }
            }
        });
    });

    document.getElementById('load-more-comments').addEventListener('click', () => fetchComments(true));
    function escapeHtml(str) { return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;"); }
    
    fetchComments();
});

function toggleTagModal(show) {
    document.getElementById('tag-modal').classList.toggle('hidden', !show);
}

// --- BONUS: Toggle User Window control helper ---
function toggleUserModal(show) {
    document.getElementById('user-modal').classList.toggle('hidden', !show);
}
</script>
@endsection