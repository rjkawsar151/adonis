<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogAuthor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class BlogAuthorController extends Controller
{
    public function index()
    {
        $authors = BlogAuthor::orderBy('created_at', 'desc')->get();
        return view('admin.authors.index', compact('authors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'designation' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'status' => 'boolean',
        ]);

        $data = $request->except('profile_photo');
        $data['status'] = $request->has('status');

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $fileName = 'auth_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/blogs'), $fileName);
            $data['profile_photo'] = '/uploads/blogs/' . $fileName;
        }

        BlogAuthor::create($data);
        Cache::forget('adonis_blogs_v2');

        return redirect()->back()->with('success', 'Author profile created successfully.');
    }

    public function update(Request $request, $id)
    {
        $author = BlogAuthor::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'designation' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'status' => 'boolean',
        ]);

        $data = $request->except('profile_photo');
        $data['status'] = $request->has('status');

        if ($request->hasFile('profile_photo')) {
            if ($author->profile_photo && File::exists(public_path($author->profile_photo))) {
                File::delete(public_path($author->profile_photo));
            }
            $file = $request->file('profile_photo');
            $fileName = 'auth_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/blogs'), $fileName);
            $data['profile_photo'] = '/uploads/blogs/' . $fileName;
        }

        $author->update($data);
        Cache::forget('adonis_blogs_v2');

        return redirect()->back()->with('success', 'Author profile updated successfully.');
    }

    public function destroy($id)
    {
        $author = BlogAuthor::findOrFail($id);
        if ($author->profile_photo && File::exists(public_path($author->profile_photo))) {
            File::delete(public_path($author->profile_photo));
        }
        $author->delete();
        Cache::forget('adonis_blogs_v2');

        return redirect()->back()->with('success', 'Author profile deleted successfully.');
    }
}
