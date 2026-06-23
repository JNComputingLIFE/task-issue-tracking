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
        <td class="p-4">
            <div class="flex -space-x-1 overflow-hidden">
                @forelse($issue->users as $u)
                    <span class="inline-block h-6 w-6 rounded-full bg-indigo-100 text-indigo-800 text-[10px] font-bold flex items-center justify-center ring-2 ring-white" title="{{ $u->name }}">
                        {{ strtoupper(substr($u->name, 0, 2)) }}
                    </span>
                @empty
                    <span class="text-xs text-gray-400">Unassigned</span>
                @endforelse
            </div>
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
        <td colspan="5" class="p-8 text-center text-gray-400">No matching issues discovered.</td>
    </tr>
@endforelse