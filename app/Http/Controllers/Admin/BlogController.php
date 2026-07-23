<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogAuthor;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\BlogSlugRedirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with(['author', 'category']);

        // Filters
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'trash') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $request->status);
            }
        } else {
            // By default don't show trashed posts
            $query->withTrashed()->whereNull('deleted_at');
        }

        if ($request->has('author_id') && $request->author_id !== '') {
            $query->where('author_id', $request->author_id);
        }

        if ($request->has('category_id') && $request->category_id !== '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('featured') && $request->featured !== '') {
            $query->where('is_featured', $request->featured == '1');
        }

        if ($request->filled('search')) {
            $search = trim($request->string('search')->toString());
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $blogs = $query->orderByDesc('createdAt')->paginate(15)->withQueryString();

        $authors = BlogAuthor::where('status', true)->get();
        $categories = BlogCategory::where('status', true)->get();

        return view('admin.blogs.index', compact('blogs', 'authors', 'categories'));
    }

    public function create()
    {
        $authors = BlogAuthor::where('status', true)->get();
        $categories = BlogCategory::where('status', true)->get();
        $tags = BlogTag::where('status', true)->get();
        return view('admin.blogs.create', compact('authors', 'categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:160|unique:blogs,slug',
            'excerpt' => 'nullable|string',
            'coverImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'coverImage_url' => 'nullable|string|max:2048',
            'contentHtml' => 'nullable|string',
            'seoTitle' => 'nullable|string|max:255',
            'seoDescription' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'author_id' => 'nullable|exists:blog_authors,id',
            'category_id' => 'nullable|exists:blog_categories,id',
            'published_at' => 'nullable|date',
            'focus_keyword' => 'nullable|string',
            'secondary_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'og_title' => 'nullable|string',
            'og_description' => 'nullable|string',
            'twitter_title' => 'nullable|string',
            'twitter_description' => 'nullable|string',
            'schema_type' => 'required|string',
            'breadcrumb_title' => 'nullable|string',
        ]);

        $coverImage = $request->input('coverImage_url') ?? '';
        if ($request->hasFile('coverImage')) {
            $coverImage = $this->uploadAndOptimizeImage($request->file('coverImage'));
        }

        // Estimate reading time: 1 min per 200 words
        $wordCount = str_word_count(strip_tags($request->contentHtml));
        $readingTime = max(1, ceil($wordCount / 200));

        $blog = Blog::create([
            'id' => $request->slug,
            'slug' => $request->slug,
            'title' => $request->title,
            'excerpt' => $request->excerpt ?? '',
            'coverImage' => $coverImage,
            'contentHtml' => $request->contentHtml ?? '',
            'seoTitle' => $request->seoTitle ?? '',
            'seoDescription' => $request->seoDescription ?? '',
            'status' => $request->status,
            'is_featured' => $request->has('is_featured'),
            'is_pinned' => $request->has('is_pinned'),
            'author_id' => $request->author_id,
            'category_id' => $request->category_id,
            'published_at' => $request->published_at,
            'reading_time' => $readingTime,
            'focus_keyword' => $request->focus_keyword,
            'secondary_keywords' => $request->secondary_keywords,
            'canonical_url' => $request->canonical_url,
            'robots_index' => $request->has('robots_index'),
            'robots_follow' => $request->has('robots_follow'),
            'og_title' => $request->og_title,
            'og_description' => $request->og_description,
            'twitter_title' => $request->twitter_title,
            'twitter_description' => $request->twitter_description,
            'schema_type' => $request->schema_type,
            'breadcrumb_title' => $request->breadcrumb_title,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        }

        $this->clearBlogCache();

        return redirect('/admin/blogs')->with('success', 'Blog post created.');
    }

    public function edit($id)
    {
        $blog = Blog::withTrashed()->findOrFail($id);
        $authors = BlogAuthor::where('status', true)->get();
        $categories = BlogCategory::where('status', true)->get();
        $tags = BlogTag::where('status', true)->get();
        $selectedTags = $blog->tags->pluck('id')->toArray();

        return view('admin.blogs.edit', compact('blog', 'authors', 'categories', 'tags', 'selectedTags'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::withTrashed()->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:160|unique:blogs,slug,' . $id . ',id',
            'excerpt' => 'nullable|string',
            'coverImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'coverImage_url' => 'nullable|string|max:2048',
            'contentHtml' => 'nullable|string',
            'seoTitle' => 'nullable|string|max:255',
            'seoDescription' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'author_id' => 'nullable|exists:blog_authors,id',
            'category_id' => 'nullable|exists:blog_categories,id',
            'published_at' => 'nullable|date',
            'focus_keyword' => 'nullable|string',
            'secondary_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'og_title' => 'nullable|string',
            'og_description' => 'nullable|string',
            'twitter_title' => 'nullable|string',
            'twitter_description' => 'nullable|string',
            'schema_type' => 'required|string',
            'breadcrumb_title' => 'nullable|string',
        ]);

        // If published slug is changed, log redirect
        if ($blog->status === 'published' && $blog->slug !== $request->slug) {
            BlogSlugRedirect::updateOrCreate(
                ['old_slug' => $blog->slug],
                ['new_slug' => $request->slug]
            );
        }

        $wordCount = str_word_count(strip_tags($request->contentHtml));
        $readingTime = max(1, ceil($wordCount / 200));

        $data = [
            'slug' => $request->slug,
            'title' => $request->title,
            'excerpt' => $request->excerpt ?? '',
            'contentHtml' => $request->contentHtml ?? '',
            'seoTitle' => $request->seoTitle ?? '',
            'seoDescription' => $request->seoDescription ?? '',
            'status' => $request->status,
            'is_featured' => $request->has('is_featured'),
            'is_pinned' => $request->has('is_pinned'),
            'author_id' => $request->author_id,
            'category_id' => $request->category_id,
            'published_at' => $request->published_at,
            'reading_time' => $readingTime,
            'focus_keyword' => $request->focus_keyword,
            'secondary_keywords' => $request->secondary_keywords,
            'canonical_url' => $request->canonical_url,
            'robots_index' => $request->has('robots_index'),
            'robots_follow' => $request->has('robots_follow'),
            'og_title' => $request->og_title,
            'og_description' => $request->og_description,
            'twitter_title' => $request->twitter_title,
            'twitter_description' => $request->twitter_description,
            'schema_type' => $request->schema_type,
            'breadcrumb_title' => $request->breadcrumb_title,
            'updatedAt' => now(),
        ];

        if ($request->hasFile('coverImage')) {
            if ($blog->coverImage && File::exists(public_path($blog->coverImage)) && !str_starts_with($blog->coverImage, 'http')) {
                File::delete(public_path($blog->coverImage));
            }
            $data['coverImage'] = $this->uploadAndOptimizeImage($request->file('coverImage'));
        } elseif ($request->has('coverImage_url')) {
            $data['coverImage'] = $request->input('coverImage_url') ?? '';
        }

        $blog->update($data);

        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        } else {
            $blog->tags()->detach();
        }

        $this->clearBlogCache($blog->id);

        return redirect('/admin/blogs')->with('success', 'Blog post updated.');
    }

    public function duplicate($id)
    {
        $blog = Blog::findOrFail($id);
        $newBlog = $blog->replicate();
        
        $newSlug = $blog->slug . '-duplicate-' . rand(10, 99);
        $newBlog->id = $newSlug;
        $newBlog->slug = $newSlug;
        $newBlog->title = $blog->title . ' (Copy)';
        $newBlog->status = 'draft';
        $newBlog->createdAt = now();
        $newBlog->updatedAt = now();
        $newBlog->save();

        // Copy pivot tags
        $newBlog->tags()->sync($blog->tags->pluck('id'));

        $this->clearBlogCache();

        return redirect()->back()->with('success', 'Blog post duplicated as draft.');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete(); // Soft delete
        $this->clearBlogCache($id);

        return redirect('/admin/blogs')->with('success', 'Blog post moved to trash.');
    }

    public function restore($id)
    {
        $blog = Blog::onlyTrashed()->findOrFail($id);
        $blog->restore();
        $this->clearBlogCache($id);

        return redirect('/admin/blogs')->with('success', 'Blog post restored.');
    }

    public function forceDelete($id)
    {
        $blog = Blog::onlyTrashed()->findOrFail($id);
        if ($blog->coverImage && File::exists(public_path($blog->coverImage))) {
            File::delete(public_path($blog->coverImage));
        }
        $blog->tags()->detach();
        $blog->forceDelete();
        $this->clearBlogCache($id);

        return redirect('/admin/blogs')->with('success', 'Blog post permanently deleted.');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'No posts selected.');
        }

        if ($action === 'publish') {
            Blog::whereIn('id', $ids)->update(['status' => 'published', 'updatedAt' => now()]);
        } elseif ($action === 'draft') {
            Blog::whereIn('id', $ids)->update(['status' => 'draft', 'updatedAt' => now()]);
        } elseif ($action === 'trash') {
            Blog::whereIn('id', $ids)->delete(); // Soft delete
        } elseif ($action === 'restore') {
            Blog::onlyTrashed()->whereIn('id', $ids)->restore();
        } elseif ($action === 'delete') {
            foreach ($ids as $id) {
                $blog = Blog::withTrashed()->find($id);
                if ($blog) {
                    if ($blog->coverImage && File::exists(public_path($blog->coverImage))) {
                        File::delete(public_path($blog->coverImage));
                    }
                    $blog->tags()->detach();
                    $blog->forceDelete();
                }
            }
        }

        $this->clearBlogCache();

        return redirect()->back()->with('success', 'Bulk action completed.');
    }

    protected function uploadAndOptimizeImage($file): string
    {
        if (!File::isDirectory(public_path('uploads/blogs'))) {
            File::makeDirectory(public_path('uploads/blogs'), 0755, true);
        }

        $fileName = 'blog_' . time() . '_' . uniqid();
        $ext = $file->getClientOriginalExtension();
        
        $tempPath = $file->getRealPath();
        $targetPath = public_path('uploads/blogs/' . $fileName . '.webp');

        // Automatic WebP Conversion using GD
        if (function_exists('imagecreatefromstring')) {
            $imgString = file_get_contents($tempPath);
            $im = imagecreatefromstring($imgString);
            if ($im !== false) {
                imagepalettetotruecolor($im);
                imagewebp($im, $targetPath, 85); // 85% compression quality
                imagedestroy($im);
                return 'uploads/blogs/' . $fileName . '.webp';
            }
        }

        // Fallback to normal upload if GD fails
        $file->move(public_path('uploads/blogs'), $fileName . '.' . $ext);
        return 'uploads/blogs/' . $fileName . '.' . $ext;
    }

    protected function clearBlogCache($id = null)
    {
        Cache::forget('adonis_blogs');
        Cache::forget('adonis_blogs_v2');
        if ($id) {
            Cache::forget("adonis_blog_related_{$id}");
        }
    }
}
