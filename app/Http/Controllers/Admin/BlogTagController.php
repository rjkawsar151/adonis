<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class BlogTagController extends Controller
{
    public function index()
    {
        $tags = BlogTag::orderBy('created_at', 'desc')->get();
        return view('admin.tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_tags,slug',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status');

        BlogTag::create($data);
        Cache::forget('adonis_blogs_v2');

        return redirect()->back()->with('success', 'Tag created successfully.');
    }

    public function update(Request $request, $id)
    {
        $tag = BlogTag::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_tags,slug,' . $id,
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status');

        $tag->update($data);
        Cache::forget('adonis_blogs_v2');

        return redirect()->back()->with('success', 'Tag updated successfully.');
    }

    public function destroy($id)
    {
        $tag = BlogTag::findOrFail($id);
        $tag->delete();
        Cache::forget('adonis_blogs_v2');

        return redirect()->back()->with('success', 'Tag deleted successfully.');
    }
}
