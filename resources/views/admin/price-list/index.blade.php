@extends('layouts.admin')

@section('title', 'Price List')
@section('page_title', 'Price List Management')

@section('admin_content')
<p class="text-sm text-gray-500 mb-6">Services are organized by category. Check items and use the bulk bar below to edit branches or delete.</p>

<!-- Bulk Action Bar -->
<div id="bulk-bar" class="hidden fixed bottom-0 left-0 right-0 z-50 bg-[#111827] border-t border-[#C9A84C]/30 px-6 py-4 shadow-2xl">
    <form action="{{ url('/admin/price-list/bulk') }}" method="POST" class="flex items-center justify-between max-w-7xl mx-auto">
        @csrf
        <div class="flex items-center gap-4">
            <span class="text-sm font-bold text-white"><span id="bulk-count">0</span> selected</span>
            <input type="hidden" name="ids" id="bulk-ids" value="">
            <button type="button" onclick="clearAll()" class="text-xs text-gray-400 hover:text-white font-semibold uppercase tracking-wider">Clear</button>
        </div>
        <div class="flex items-center gap-3">
            <select name="action" class="px-3 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]">
                <option value="change_branch">Change Branch</option>
                <option value="delete">Delete Selected</option>
            </select>
            <select name="branch_id" class="px-3 py-2 bg-[#1a1f2e] border border-gray-700 text-sm text-white focus:outline-none focus:border-[#C9A84C]" id="branch-select">
                <option value="gulshan">Gulshan</option>
                <option value="bashundhara">Bashundhara</option>
                <option value="all">All Branches</option>
            </select>
            <button type="submit" onclick="return confirm('Apply bulk action to selected items?')" class="px-5 py-2 text-xs font-bold uppercase tracking-wider text-black bg-[#C9A84C] hover:bg-[#b8973f] transition-colors">
                Apply
            </button>
        </div>
    </form>
</div>

<script>
const branchSelect = document.getElementById('branch-select');
document.querySelector('[name="action"]')?.addEventListener('change', function() {
    branchSelect.style.display = this.value === 'delete' ? 'none' : 'inline-block';
});
</script>

@foreach($grouped as $category => $items)
<div class="bg-[#111827] border border-gray-800 overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-800 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <input type="checkbox" class="category-select-all w-4 h-4 accent-[#C9A84C] cursor-pointer" data-category="{{ $loop->index }}" title="Select all in {{ $category }}">
            <div class="w-2 h-2 rounded-full bg-[#C9A84C]"></div>
            <h3 class="font-bold text-lg text-white">{{ $category }}</h3>
            <span class="text-xs text-gray-500 font-semibold">({{ count($items) }} items)</span>
        </div>
        <a href="{{ url('/admin/price-list/create?category=' . urlencode($category)) }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold uppercase tracking-wider text-black bg-[#C9A84C] hover:bg-[#b8973f] shadow-md hover:shadow-lg transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Item
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#0c0f15] border-b border-gray-800 text-xs font-bold uppercase tracking-wider text-gray-500">
                    <th class="px-4 py-4 w-10"></th>
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Service Name</th>
                    <th class="px-6 py-4">Description</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">Branch</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm font-medium text-gray-300">
                @forelse($items as $item)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors">
                        <td class="px-4 py-4">
                            <input type="checkbox" class="item-checkbox row-checkbox" value="{{ $item->id }}" data-category="{{ $loop->parent->index }}">
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $item->sort_order }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-white">{{ $item->name }}</div>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <span class="text-xs text-gray-400 line-clamp-2">{{ $item->description ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-[#C9A84C]">৳{{ $item->price }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-semibold text-gray-400">
                                @if($item->branch_id === 'all' || !$item->branch_id)
                                    All Branches
                                @elseif($item->branch_id === 'gulshan')
                                    Gulshan
                                @elseif($item->branch_id === 'bashundhara')
                                    Bashundhara
                                @else
                                    {{ $item->branch_id }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ url('/admin/price-list/' . $item->id . '/edit') }}" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-[#C9A84C] hover:text-black transition-colors" title="Edit Item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ url('/admin/price-list/' . $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this item?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 text-gray-400 hover:bg-red-600 hover:text-white transition-colors" title="Delete Item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-600">
                            No items in this category.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endforeach

<script>
const bulkBar = document.getElementById('bulk-bar');
const bulkIds = document.getElementById('bulk-ids');
const bulkCount = document.getElementById('bulk-count');

function updateBulkBar() {
    const checked = document.querySelectorAll('.item-checkbox:checked');
    const ids = Array.from(checked).map(cb => cb.value);
    bulkCount.textContent = ids.length;
    bulkIds.value = ids.join(',');
    bulkBar.classList.toggle('hidden', ids.length === 0);
}

document.querySelectorAll('.item-checkbox').forEach(cb => {
    cb.addEventListener('change', updateBulkBar);
});

document.querySelectorAll('.category-select-all').forEach(selAll => {
    selAll.addEventListener('change', function() {
        const catIdx = this.dataset.category;
        document.querySelectorAll(`.item-checkbox[data-category="${catIdx}"]`).forEach(cb => {
            cb.checked = this.checked;
        });
        updateBulkBar();
    });
});

function clearAll() {
    document.querySelectorAll('.item-checkbox:checked').forEach(cb => cb.checked = false);
    document.querySelectorAll('.category-select-all').forEach(cb => cb.checked = false);
    updateBulkBar();
}

// Hide branch select when delete is chosen
document.querySelector('[name="action"]')?.addEventListener('change', function() {
    const bs = document.getElementById('branch-select');
    if (bs) bs.style.display = this.value === 'delete' ? 'none' : 'inline-block';
});
</script>
@endsection
