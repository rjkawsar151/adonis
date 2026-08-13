<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::orderBy('created_at', 'desc')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data = $request->except('featured_image');
        $data['status'] = $request->has('status');

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = '/' . ImageCompressor::compressAndSaveWebp($request->file('featured_image'), 'uploads/blogs', 70);
        }

        BlogCategory::create($data);
        Cache::forget('adonis_blogs_v2');

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug,' . $id,
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $data = $request->except('featured_image');
        $data['status'] = $request->has('status');

        if ($request->hasFile('featured_image')) {
            if ($category->featured_image && File::exists(public_path($category->featured_image))) {
                File::delete(public_path($category->featured_image));
            }
            $data['featured_image'] = '/' . ImageCompressor::compressAndSaveWebp($request->file('featured_image'), 'uploads/blogs', 70);
        }

        $category->update($data);
        Cache::forget('adonis_blogs_v2');

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = BlogCategory::findOrFail($id);
        if ($category->featured_image && File::exists(public_path($category->featured_image))) {
            File::delete(public_path($category->featured_image));
        }
        $category->delete();
        Cache::forget('adonis_blogs_v2');

        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
