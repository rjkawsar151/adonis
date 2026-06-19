@extends('layouts.admin')

@section('title', 'Add Blog')
@section('page_title', 'Write Blog Post')

@section('admin_content')
<form action="{{ url('/admin/blogs') }}" method="POST" enctype="multipart/form-data" class="max-w-3xl bg-[#111827] border border-gray-800 p-8 space-y-6">
    @csrf

    <div class="flex items-center justify-between border-b border-gray-800 pb-4">
        <h4 class="font-bold text-sm uppercase tracking-wider text-[#C9A84C]">New Post</h4>
        <select name="status" class="bg-[#0c0f15] text-white text-xs border border-gray-700 px-3 py-2 focus:outline-none focus:border-[#C9A84C]">
            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
        </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Headline</label>
            <input type="text" name="title" value="{{ old('title') }}" required class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
        </div>
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">URL Slug</label>
            <input type="text" name="slug" value="{{ old('slug') }}" required class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
        </div>
        <div class="md:col-span-2">
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Short Excerpt</label>
            <textarea name="excerpt" rows="2" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C] resize-none">{{ old('excerpt') }}</textarea>
        </div>
        <div class="md:col-span-2">
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Cover Image</label>
            <input type="file" name="coverImage" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml,image/webp" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C] file:mr-3 file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-wider file:text-black file:bg-[#C9A84C] file:cursor-pointer hover:file:bg-[#b8973f]">
            <div id="cover-preview" class="mt-2 hidden w-28 h-20 border border-gray-700 overflow-hidden bg-black">
                <img id="cover-preview-img" src="" alt="Preview" class="h-full w-full object-cover">
            </div>
        </div>
        <div class="md:col-span-2">
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">Content (HTML)</label>
            <div class="border border-gray-700 bg-[#0c0f15] overflow-hidden">
                <!-- Toolbar -->
                <div class="flex flex-wrap gap-1 p-2 border-b border-gray-700 bg-[#111827]">
                    <button type="button" onclick="execCmd('formatBlock','h2')" class="px-3 py-1.5 bg-black text-white hover:text-[#C9A84C] text-[10px] font-mono uppercase border border-gray-700">H2</button>
                    <button type="button" onclick="execCmd('formatBlock','h3')" class="px-3 py-1.5 bg-black text-white hover:text-[#C9A84C] text-[10px] font-mono uppercase border border-gray-700">H3</button>
                    <button type="button" onclick="execCmd('bold')" class="px-3 py-1.5 bg-black text-white hover:text-[#C9A84C] text-[10px] font-bold border border-gray-700">B</button>
                    <button type="button" onclick="execCmd('italic')" class="px-3 py-1.5 bg-black text-white hover:text-[#C9A84C] text-[10px] italic border border-gray-700">I</button>
                    <button type="button" onclick="execCmd('underline')" class="px-3 py-1.5 bg-black text-white hover:text-[#C9A84C] text-[10px] underline border border-gray-700">U</button>
                    <button type="button" onclick="execCmd('insertUnorderedList')" class="px-3 py-1.5 bg-black text-white hover:text-[#C9A84C] text-[10px] border border-gray-700">Bullet</button>
                    <button type="button" onclick="execCmd('insertOrderedList')" class="px-3 py-1.5 bg-black text-white hover:text-[#C9A84C] text-[10px] border border-gray-700">Number</button>
                </div>
                <div
                    id="blog-editor"
                    contenteditable="true"
                    class="min-h-[300px] p-5 text-gray-300 text-sm focus:outline-none overflow-auto"
                ><h2>Article Headline</h2><p>Start writing your salon blog content here.</p></div>
                <textarea name="contentHtml" id="contentHtml" class="hidden">{{ old('contentHtml') }}</textarea>
            </div>
        </div>
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">SEO Title</label>
            <input type="text" name="seoTitle" value="{{ old('seoTitle') }}" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
        </div>
        <div>
            <label class="block text-[10px] font-mono uppercase tracking-widest text-gray-500 mb-1.5">SEO Description</label>
            <textarea name="seoDescription" rows="2" class="w-full bg-[#0c0f15] text-white text-sm border border-gray-700 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C] resize-none">{{ old('seoDescription') }}</textarea>
        </div>
    </div>

    <div class="flex gap-4 pt-2">
        <button type="submit" class="px-6 py-2.5 bg-[#C9A84C] hover:bg-[#b8973f] text-black text-xs font-bold uppercase tracking-wider transition-colors">Save Blog Post</button>
        <a href="{{ url('/admin/blogs') }}" class="px-6 py-2.5 border border-gray-700 text-gray-300 hover:border-gray-500 text-xs font-bold uppercase tracking-wider transition-colors">Cancel</a>
    </div>
</form>

<script>
function execCmd(command, value) {
    const editor = document.getElementById('blog-editor');
    editor.focus();
    document.execCommand(command, false, value);
    document.getElementById('contentHtml').value = editor.innerHTML;
}
document.addEventListener('DOMContentLoaded', function() {
    const editor = document.getElementById('blog-editor');
    const hidden = document.getElementById('contentHtml');
    editor.addEventListener('input', function() {
        hidden.value = this.innerHTML;
    });
    @if(old('contentHtml'))
        editor.innerHTML = {{ Illuminate\Support\Js::from(old('contentHtml')) }};
        hidden.value = editor.innerHTML;
    @endif
});
document.querySelector('input[name="coverImage"]')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('cover-preview-img').src = ev.target.result;
            document.getElementById('cover-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
