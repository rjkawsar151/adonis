<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::orderBy('createdAt', 'desc')->get();
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:160|unique:blogs,slug',
            'excerpt' => 'nullable|string',
            'coverImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'contentHtml' => 'nullable|string',
            'seoTitle' => 'nullable|string|max:255',
            'seoDescription' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ]);

        $now = now();

        $coverImage = '';
        if ($request->hasFile('coverImage')) {
            if (!File::isDirectory(public_path('uploads/blogs'))) {
                File::makeDirectory(public_path('uploads/blogs'), 0755, true);
            }
            $file = $request->file('coverImage');
            $fileName = 'blog_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/blogs'), $fileName);
            $coverImage = 'uploads/blogs/' . $fileName;
        }

        Blog::create([
            'id' => $request->slug,
            'slug' => $request->slug,
            'title' => $request->title,
            'excerpt' => $request->excerpt ?? '',
            'coverImage' => $coverImage,
            'contentHtml' => $request->contentHtml ?? '',
            'seoTitle' => $request->seoTitle ?? '',
            'seoDescription' => $request->seoDescription ?? '',
            'status' => $request->status,
            'createdAt' => $now,
            'updatedAt' => $now,
        ]);

        return redirect('/admin/blogs')->with('success', 'Blog post created.');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:160|unique:blogs,slug,' . $id . ',id',
            'excerpt' => 'nullable|string',
            'coverImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'contentHtml' => 'nullable|string',
            'seoTitle' => 'nullable|string|max:255',
            'seoDescription' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ]);

        $data = [
            'slug' => $request->slug,
            'title' => $request->title,
            'excerpt' => $request->excerpt ?? '',
            'contentHtml' => $request->contentHtml ?? '',
            'seoTitle' => $request->seoTitle ?? '',
            'seoDescription' => $request->seoDescription ?? '',
            'status' => $request->status,
            'updatedAt' => now(),
        ];

        if ($request->hasFile('coverImage')) {
            if (!File::isDirectory(public_path('uploads/blogs'))) {
                File::makeDirectory(public_path('uploads/blogs'), 0755, true);
            }
            if ($blog->coverImage && File::exists(public_path($blog->coverImage))) {
                File::delete(public_path($blog->coverImage));
            }
            $file = $request->file('coverImage');
            $fileName = 'blog_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/blogs'), $fileName);
            $data['coverImage'] = 'uploads/blogs/' . $fileName;
        }

        $blog->update($data);

        return redirect('/admin/blogs')->with('success', 'Blog post updated.');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->coverImage && File::exists(public_path($blog->coverImage))) {
            File::delete(public_path($blog->coverImage));
        }
        $blog->delete();
        return redirect('/admin/blogs')->with('success', 'Blog post deleted.');
    }
}
