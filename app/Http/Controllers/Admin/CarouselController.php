<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarouselImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CarouselController extends Controller
{
    public function index()
    {
        $images = CarouselImage::where('status', 'active')->orderBy('sort_order')->get();
        return view('admin.carousel', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        if (!File::isDirectory(public_path('uploads/carousel'))) {
            File::makeDirectory(public_path('uploads/carousel'), 0755, true);
        }

        $maxOrder = CarouselImage::max('sort_order') ?? 0;

        foreach ($request->file('images') as $i => $file) {
            $fileName = 'carousel_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/carousel'), $fileName);

            CarouselImage::create([
                'image_path' => 'uploads/carousel/' . $fileName,
                'alt_text' => null,
                'link_url' => null,
                'sort_order' => $maxOrder + $i + 1,
                'status' => 'active',
            ]);
        }

        $count = count($request->file('images'));
        return back()->with('success', "$count carousel image(s) added successfully.");
    }

    public function destroy($id)
    {
        $image = CarouselImage::findOrFail($id);
        if (File::exists(public_path($image->image_path))) {
            File::delete(public_path($image->image_path));
        }
        $image->delete();

        return back()->with('success', 'Carousel image removed.');
    }
}
