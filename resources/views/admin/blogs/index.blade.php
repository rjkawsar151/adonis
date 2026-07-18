@extends('layouts.admin')

@section('title', 'Manage Blog Posts')
@section('page_title', 'Blog Content Directory')

@section('admin_content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
        <div>
            <p class="text-sm text-gray-400">Create, organize, schedule, and publish Adonis journal articles.</p>
            <p class="mt-1 text-xs text-gray-600">{{ $blogs->total() }} {{ Str::plural('article', $blogs->total()) }} found</p>
        </div>
        <nav class="flex flex-wrap gap-2" aria-label="Blog administration">
            <a href="{{ route('admin.blog-categories.index') }}" class="inline-flex items-center px-3.5 py-2 text-[11px] font-bold uppercase tracking-wider text-gray-300 bg-[#111827] border border-gray-700 hover:border-[#C9A84C]/60 hover:text-[#C9A84C] transition-colors">Categories</a>
            <a href="{{ route('admin.blog-tags.index') }}" class="inline-flex items-center px-3.5 py-2 text-[11px] font-bold uppercase tracking-wider text-gray-300 bg-[#111827] border border-gray-700 hover:border-[#C9A84C]/60 hover:text-[#C9A84C] transition-colors">Tags</a>
            <a href="{{ route('admin.blog-authors.index') }}" class="inline-flex items-center px-3.5 py-2 text-[11px] font-bold uppercase tracking-wider text-gray-300 bg-[#111827] border border-gray-700 hover:border-[#C9A84C]/60 hover:text-[#C9A84C] transition-colors">Authors</a>
            <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-[11px] font-extrabold uppercase tracking-wider text-black bg-[#C9A84C] hover:bg-[#b8973f] transition-colors">
                <span class="text-base leading-none">+</span> Create Post
            </a>
        </nav>
    </div>

    @if(session('success'))
        <div class="border border-green-800/50 bg-green-900/20 px-4 py-3 text-sm text-green-400">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="border border-red-800/50 bg-red-900/20 px-4 py-3 text-sm text-red-400">{{ session('error') }}</div>
    @endif

    <section class="bg-[#111827] border border-gray-800" aria-labelledby="blog-filters-title">
        <div class="px-5 py-4 border-b border-gray-800">
            <h3 id="blog-filters-title" class="text-xs font-bold uppercase tracking-[0.16em] text-white">Filters & Search</h3>
        </div>
        <form action="{{ route('admin.blogs.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-4 p-5">
            <div class="sm:col-span-2 xl:col-span-2">
                <label for="blog-search" class="block mb-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-500">Search</label>
                <input id="blog-search" type="search" name="search" value="{{ request('search') }}" placeholder="Title, slug, or excerpt" class="w-full h-10 px-3 bg-[#0c0f15] border border-gray-700 text-sm text-white placeholder:text-gray-600 focus:outline-none focus:border-[#C9A84C]">
            </div>
            <div>
                <label for="blog-status" class="block mb-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-500">Status</label>
                <select id="blog-status" name="status" class="w-full h-10 px-3 bg-[#0c0f15] border border-gray-700 text-sm text-gray-200 focus:outline-none focus:border-[#C9A84C]">
                    <option value="">All statuses</option>
                    <option value="published" @selected(request('status') === 'published')>Published</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    <option value="trash" @selected(request('status') === 'trash')>Trash</option>
                </select>
            </div>
            <div>
                <label for="blog-author" class="block mb-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-500">Author</label>
                <select id="blog-author" name="author_id" class="w-full h-10 px-3 bg-[#0c0f15] border border-gray-700 text-sm text-gray-200 focus:outline-none focus:border-[#C9A84C]">
                    <option value="">All authors</option>
                    @foreach($authors as $author)<option value="{{ $author->id }}" @selected((string) request('author_id') === (string) $author->id)>{{ $author->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="blog-category" class="block mb-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-500">Category</label>
                <select id="blog-category" name="category_id" class="w-full h-10 px-3 bg-[#0c0f15] border border-gray-700 text-sm text-gray-200 focus:outline-none focus:border-[#C9A84C]">
                    <option value="">All categories</option>
                    @foreach($categories as $category)<option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label for="blog-featured" class="block mb-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-500">Visibility</label>
                <select id="blog-featured" name="featured" class="w-full h-10 px-3 bg-[#0c0f15] border border-gray-700 text-sm text-gray-200 focus:outline-none focus:border-[#C9A84C]">
                    <option value="">All articles</option>
                    <option value="1" @selected(request('featured') === '1')>Featured only</option>
                </select>
            </div>
            <div class="sm:col-span-2 xl:col-span-6 flex flex-wrap justify-end gap-2 pt-1">
                @if(request()->hasAny(['search', 'status', 'author_id', 'category_id', 'featured']))
                    <a href="{{ route('admin.blogs.index') }}" class="inline-flex h-10 items-center px-4 text-[11px] font-bold uppercase tracking-wider text-gray-400 border border-gray-700 hover:text-white hover:border-gray-500">Reset</a>
                @endif
                <button type="submit" class="inline-flex h-10 items-center px-5 text-[11px] font-extrabold uppercase tracking-wider text-black bg-[#C9A84C] hover:bg-[#b8973f]">Apply Filters</button>
            </div>
        </form>
    </section>

    <form action="{{ route('admin.blogs.bulk') }}" method="POST" id="bulkForm" class="bg-[#111827] border border-gray-800 overflow-hidden">
        @csrf
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-5 py-4 border-b border-gray-800 bg-[#0c0f15]">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-[0.16em] text-white">Articles</h3>
                <span id="selection-count" class="text-[10px] text-gray-600">0 selected</span>
            </div>
            <div class="flex w-full sm:w-auto gap-2">
                <label for="bulk-action" class="sr-only">Bulk action</label>
                <select id="bulk-action" name="action" required class="min-w-0 flex-1 sm:w-48 h-9 px-3 bg-[#111827] border border-gray-700 text-xs text-gray-300 focus:outline-none focus:border-[#C9A84C]">
                    <option value="">Bulk actions</option>
                    <option value="publish">Mark published</option>
                    <option value="draft">Mark draft</option>
                    <option value="trash">Move to trash</option>
                    @if(request('status') === 'trash')
                        <option value="restore">Restore selected</option>
                        <option value="delete">Delete permanently</option>
                    @endif
                </select>
                <button type="submit" class="h-9 px-4 text-[10px] font-bold uppercase tracking-wider border border-[#C9A84C]/60 text-[#C9A84C] hover:bg-[#C9A84C] hover:text-black">Apply</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1080px] table-fixed text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-800 text-[10px] font-bold uppercase tracking-wider text-gray-500">
                        <th class="w-12 px-4 py-3 text-center"><input type="checkbox" id="checkAll" class="accent-[#C9A84C]" aria-label="Select all articles"></th>
                        <th class="w-24 px-3 py-3">Image</th>
                        <th class="w-[30%] px-4 py-3">Article</th>
                        <th class="w-36 px-4 py-3">Taxonomy</th>
                        <th class="w-48 px-4 py-3">Status</th>
                        <th class="w-44 px-4 py-3">Dates</th>
                        <th class="w-48 px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-sm text-gray-300">
                    @forelse($blogs as $blog)
                        @php
                            $imageUrl = null;
                            if ($blog->coverImage) {
                                $imageUrl = Str::startsWith($blog->coverImage, ['http://', 'https://', '//'])
                                    ? $blog->coverImage
                                    : asset(ltrim($blog->coverImage, '/'));
                            }
                            $isScheduled = $blog->published_at && $blog->published_at->isFuture();
                        @endphp
                        <tr class="hover:bg-gray-800/30 transition-colors {{ $blog->trashed() ? 'bg-red-950/5 opacity-70' : '' }}">
                            <td class="px-4 py-4 text-center align-middle"><input type="checkbox" name="ids[]" value="{{ $blog->id }}" class="bulk-check accent-[#C9A84C]" aria-label="Select {{ $blog->title }}"></td>
                            <td class="px-3 py-4 align-middle">
                                <div class="h-14 w-20 overflow-hidden border border-gray-700 bg-[#0c0f15]">
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="" class="h-full w-full object-cover" loading="lazy" onerror="this.hidden=true;this.nextElementSibling.classList.remove('hidden')">
                                    @endif
                                    <div class="{{ $imageUrl ? 'hidden' : '' }} h-full w-full items-center justify-center text-center text-[9px] uppercase tracking-wider text-gray-600 {{ $imageUrl ? '' : 'flex' }}">No image</div>
                                </div>
                            </td>
                            <td class="px-4 py-4 align-middle">
                                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="block truncate font-semibold text-white hover:text-[#C9A84C]" title="{{ $blog->title }}">{{ $blog->title }}</a>
                                <div class="mt-1 truncate font-mono text-[10px] text-gray-600" title="{{ $blog->slug }}">/blog/{{ $blog->slug }}</div>
                                @if($blog->excerpt)<p class="mt-1 line-clamp-1 text-xs text-gray-500">{{ Str::limit(strip_tags($blog->excerpt), 80) }}</p>@endif
                            </td>
                            <td class="px-4 py-4 align-middle">
                                <div class="truncate text-xs text-gray-300">{{ $blog->category->name ?? 'Uncategorized' }}</div>
                                <div class="mt-1 truncate text-[10px] text-gray-600">{{ $blog->author->name ?? 'No author' }}</div>
                            </td>
                            <td class="px-4 py-4 align-middle">
                                <div class="flex flex-wrap gap-1.5">
                                    @if($blog->is_featured)<span class="border border-[#C9A84C]/30 bg-[#C9A84C]/10 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-[#C9A84C]">Featured</span>@endif
                                    @if($blog->is_pinned)<span class="border border-blue-700/40 bg-blue-900/20 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-blue-400">Pinned</span>@endif
                                    @if($blog->status === 'published')<span class="border border-green-800/50 bg-green-900/20 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-green-400">Published</span>@else<span class="border border-gray-700 bg-gray-800 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-gray-400">Draft</span>@endif
                                    @if($isScheduled)<span class="border border-purple-800/50 bg-purple-900/20 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-purple-400">Scheduled</span>@endif
                                    @if($blog->trashed())<span class="border border-red-800/50 bg-red-900/20 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-red-400">Trashed</span>@endif
                                </div>
                            </td>
                            <td class="px-4 py-4 align-middle text-xs">
                                @if($blog->published_at)
                                    <div class="text-gray-300">{{ $isScheduled ? 'Publishes' : 'Published' }}</div>
                                    <time class="mt-1 block text-[10px] text-gray-600" datetime="{{ $blog->published_at->toIso8601String() }}">{{ $blog->published_at->format('M d, Y · H:i') }}</time>
                                @else
                                    <div class="text-gray-400">Created</div>
                                    <time class="mt-1 block text-[10px] text-gray-600" datetime="{{ optional($blog->createdAt)->toIso8601String() }}">{{ optional($blog->createdAt)->format('M d, Y') ?? 'Unknown' }}</time>
                                @endif
                            </td>
                            <td class="px-4 py-4 align-middle">
                                <div class="flex flex-wrap justify-end gap-2">
                                    @if($blog->trashed())
                                        <button type="submit" formaction="{{ route('admin.blogs.restore', $blog->id) }}" class="px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-wider text-green-400 border border-green-800/50 hover:bg-green-900/30">Restore</button>
                                        <button type="submit" formaction="{{ route('admin.blogs.force-delete', $blog->id) }}" formmethod="POST" name="_method" value="DELETE" onclick="return confirm('Permanently delete this article? This cannot be undone.')" class="px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-wider text-red-400 border border-red-800/50 hover:bg-red-900/30">Delete</button>
                                    @else
                                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-wider text-blue-400 border border-blue-800/50 hover:bg-blue-900/30">Edit</a>
                                        <button type="submit" formaction="{{ route('admin.blogs.duplicate', $blog->id) }}" class="px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-wider text-gray-300 border border-gray-700 hover:bg-gray-800">Copy</button>
                                        <button type="submit" formaction="{{ route('admin.blogs.destroy', $blog->id) }}" name="_method" value="DELETE" onclick="return confirm('Move this article to trash?')" class="px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-wider text-red-400 border border-red-800/50 hover:bg-red-900/30">Trash</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-16 text-center"><div class="text-sm font-semibold text-gray-400">No articles found</div><p class="mt-1 text-xs text-gray-600">Adjust the filters or create your first blog post.</p><a href="{{ route('admin.blogs.create') }}" class="mt-4 inline-flex px-4 py-2 text-[10px] font-bold uppercase tracking-wider bg-[#C9A84C] text-black">Create Post</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    @if($blogs->hasPages())
        <div class="blog-pagination">{{ $blogs->onEachSide(1)->links() }}</div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const checkAll = document.getElementById('checkAll');
    const checks = Array.from(document.querySelectorAll('.bulk-check'));
    const count = document.getElementById('selection-count');
    const update = () => {
        const selected = checks.filter(input => input.checked).length;
        count.textContent = `${selected} selected`;
        if (checkAll) {
            checkAll.checked = selected > 0 && selected === checks.length;
            checkAll.indeterminate = selected > 0 && selected < checks.length;
        }
    };
    checkAll?.addEventListener('change', () => { checks.forEach(input => input.checked = checkAll.checked); update(); });
    checks.forEach(input => input.addEventListener('change', update));
    document.getElementById('bulkForm')?.addEventListener('submit', event => {
        const submitter = event.submitter;
        if (submitter?.hasAttribute('formaction')) return;
        if (!checks.some(input => input.checked)) {
            event.preventDefault();
            window.alert('Select at least one article before applying a bulk action.');
        } else if (!window.confirm('Apply this action to the selected articles?')) {
            event.preventDefault();
        }
    });
    update();
});
</script>
@endsection
