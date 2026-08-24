<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class OfferController extends Controller
{
    public function index()
    {
        $offers = DB::table('offers')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        return view('admin.offers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'subtitle'         => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'services'         => 'nullable|string',
            'badge'            => 'nullable|string|max:255',
            'icon'             => 'nullable|string|max:100',
            'original_price'   => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'image_url'        => 'nullable|string|max:2048',
            'valid_until'      => 'nullable|string|max:255',
            'branch'           => 'required|in:all,gulshan,bashundhara',
            'is_active'        => 'nullable|boolean',
            'sort_order'       => 'required|integer|min:0',
        ]);

        $image = $request->input('image_url') ?? null;
        if ($request->hasFile('image')) {
            $image = asset(ImageCompressor::compressAndSaveWebp($request->file('image'), 'uploads/offers', 70));
        }

        DB::table('offers')->insert([
            'title'            => $request->input('title'),
            'subtitle'         => $request->input('subtitle'),
            'description'      => $request->input('description'),
            'services'         => $request->input('services'),
            'badge'            => $request->input('badge'),
            'icon'             => $request->input('icon') ?: 'Tag',
            'original_price'   => $request->filled('original_price') ? $request->input('original_price') : null,
            'discounted_price' => $request->filled('discounted_price') ? $request->input('discounted_price') : null,
            'discount_percent' => $request->filled('discount_percent') ? $request->input('discount_percent') : null,
            'image'            => $image,
            'valid_until'      => $request->input('valid_until'),
            'branch'           => $request->input('branch'),
            'is_active'        => $request->boolean('is_active'),
            'sort_order'       => (int) $request->input('sort_order'),
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        $this->clearOfferCache();

        return redirect('/admin/offers')->with('success', 'Offer created.');
    }

    public function edit($id)
    {
        $offer = DB::table('offers')->where('id', $id)->first();

        if (!$offer) {
            return redirect('/admin/offers')->with('error', 'Offer not found.');
        }

        return view('admin.offers.edit', compact('offer'));
    }

    public function update(Request $request, $id)
    {
        $offer = DB::table('offers')->where('id', $id)->first();

        if (!$offer) {
            return redirect('/admin/offers')->with('error', 'Offer not found.');
        }

        $request->validate([
            'title'            => 'required|string|max:255',
            'subtitle'         => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'services'         => 'nullable|string',
            'badge'            => 'nullable|string|max:255',
            'icon'             => 'nullable|string|max:100',
            'original_price'   => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'image_url'        => 'nullable|string|max:2048',
            'valid_until'      => 'nullable|string|max:255',
            'branch'           => 'required|in:all,gulshan,bashundhara',
            'is_active'        => 'nullable|boolean',
            'sort_order'       => 'required|integer|min:0',
        ]);

        $image = $offer->image;
        $newImage = $request->input('image_url');

        if ($request->hasFile('image')) {
            if ($image && File::exists(public_path($this->assetToPublicPath($image)))) {
                File::delete(public_path($this->assetToPublicPath($image)));
            }
            $image = asset(ImageCompressor::compressAndSaveWebp($request->file('image'), 'uploads/offers', 70));
        } elseif ($request->filled('image_url')) {
            $image = $newImage;
        }

        DB::table('offers')->where('id', $id)->update([
            'title'            => $request->input('title'),
            'subtitle'         => $request->input('subtitle'),
            'description'      => $request->input('description'),
            'services'         => $request->input('services'),
            'badge'            => $request->input('badge'),
            'icon'             => $request->input('icon') ?: 'Tag',
            'original_price'   => $request->filled('original_price') ? $request->input('original_price') : null,
            'discounted_price' => $request->filled('discounted_price') ? $request->input('discounted_price') : null,
            'discount_percent' => $request->filled('discount_percent') ? $request->input('discount_percent') : null,
            'image'            => $image,
            'valid_until'      => $request->input('valid_until'),
            'branch'           => $request->input('branch'),
            'is_active'        => $request->boolean('is_active'),
            'sort_order'       => (int) $request->input('sort_order'),
            'updated_at'       => now(),
        ]);

        $this->clearOfferCache();

        return redirect('/admin/offers')->with('success', 'Offer updated.');
    }

    public function destroy($id)
    {
        $offer = DB::table('offers')->where('id', $id)->first();

        if ($offer) {
            if ($offer->image) {
                $localPath = File::exists(public_path($offer->image))
                    ? $offer->image
                    : $this->assetToPublicPath($offer->image);
                if (File::exists(public_path($localPath))) {
                    File::delete(public_path($localPath));
                }
            }
            DB::table('offers')->where('id', $id)->delete();
        }

        $this->clearOfferCache();

        return redirect('/admin/offers')->with('success', 'Offer deleted.');
    }

    private function clearOfferCache(): void
    {
        Cache::forget('adonis_offers');
        Cache::forget('adonis_frontend_data');
    }

    private function assetToPublicPath(string $image): string
    {
        $url = parse_url($image);
        return isset($url['path']) ? ltrim($url['path'], '/') : $image;
    }
}