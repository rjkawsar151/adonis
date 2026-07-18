@extends('layouts.admin')

@section('title', 'Manage Employment Types')
@section('page_title', 'Employment Types Directory')

@section('admin_content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Add/Edit form -->
    <div class="bg-[#111827] border border-gray-800 p-6 shadow-sm self-start">
        <h3 class="text-sm font-bold uppercase tracking-wider text-white mb-4" id="form-title">Create Employment Type</h3>
        
        <form id="type-form" action="{{ route('admin.employment-types.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Employment Type Name</label>
                    <input type="text" name="name" id="type-name" required placeholder="e.g. Full-Time" class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Status</label>
                    <select name="status" id="type-status" required class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" id="submit-btn" class="flex-1 py-2.5 bg-[#C9A84C] hover:bg-[#b8973f] text-black text-xs font-bold uppercase tracking-widest transition-colors">
                        Save Type
                    </button>
                    <button type="button" id="cancel-btn" class="hidden px-4 py-2.5 bg-gray-800 hover:bg-gray-700 text-gray-400 text-xs font-bold uppercase tracking-widest transition-colors" onclick="resetForm()">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Listings -->
    <div class="lg:col-span-2 bg-[#111827] border border-gray-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#0c0f15] border-b border-gray-800 text-xs font-bold uppercase tracking-wider text-gray-500">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Slug</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-300">
                    @forelse($types as $type)
                        <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors {{ $type->trashed() ? 'opacity-50 bg-red-950/5' : '' }}">
                            <td class="px-6 py-4 text-xs text-gray-500">#{{ $type->id }}</td>
                            <td class="px-6 py-4 font-semibold text-white">
                                {{ $type->name }}
                                @if($type->trashed())
                                    <span class="ml-2 text-[10px] bg-red-900/30 text-red-400 border border-red-800/50 px-1.5 py-0.5 uppercase tracking-wider font-bold">Deleted</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-400">{{ $type->slug }}</td>
                            <td class="px-6 py-4">
                                @if($type->status === 'active')
                                    <span class="text-xs font-bold bg-green-900/30 text-green-400 px-2.5 py-1 border border-green-800/50">Active</span>
                                @else
                                    <span class="text-xs font-bold bg-gray-800 text-gray-500 px-2.5 py-1">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-1.5">
                                @if(!$type->trashed())
                                    <button onclick="editType({{ $type->id }}, '{{ $type->name }}', '{{ $type->status }}')" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-[#C9A84C] hover:text-black transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <form action="{{ route('admin.employment-types.destroy', $type->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Soft-delete this employment type?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-red-600 hover:text-white transition-colors" title="Soft Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.employment-types.restore', $type->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-green-400 hover:bg-green-600 hover:text-white transition-colors" title="Restore">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.5" /></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.employment-types.force-delete', $type->id) }}" method="POST" class="inline-block" onsubmit="return confirm('PERMANENTLY delete this employment type? This cannot be undone!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-red-500 hover:bg-red-700 hover:text-white transition-colors" title="Permanently Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-600">No employment types found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function editType(id, name, status) {
        document.getElementById('form-title').innerText = 'Edit Employment Type';
        document.getElementById('submit-btn').innerText = 'Update Type';
        document.getElementById('cancel-btn').classList.remove('hidden');
        
        const form = document.getElementById('type-form');
        form.action = '{{ url("/admin/employment-types") }}/' + id;
        document.getElementById('form-method').value = 'PUT';
        document.getElementById('type-name').value = name;
        document.getElementById('type-status').value = status;
    }

    function resetForm() {
        document.getElementById('form-title').innerText = 'Create Employment Type';
        document.getElementById('submit-btn').innerText = 'Save Type';
        document.getElementById('cancel-btn').classList.add('hidden');
        
        const form = document.getElementById('type-form');
        form.action = '{{ route("admin.employment-types.store") }}';
        document.getElementById('form-method').value = 'POST';
        document.getElementById('type-name').value = '';
        document.getElementById('type-status').value = 'active';
    }
</script>
@endsection
