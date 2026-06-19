<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\PriceListItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $gulshan = Service::where('branch_id', 'gulshan')->orderBy('sort_order')->get();
        $bashundhara = Service::where('branch_id', 'bashundhara')->orderBy('sort_order')->get();

        return view('admin.services.index', compact('gulshan', 'bashundhara'));
    }

    public function create()
    {
        $presetBranch = request('branch', '');
        return view('admin.services.create', compact('presetBranch'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug',
            'short_description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'branch_id' => 'required|in:gulshan,bashundhara',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'required|integer',
        ]);

        Service::create($request->only([
            'title', 'slug', 'short_description', 'price', 'branch_id', 'status', 'sort_order',
        ]));

        return redirect('/admin/services')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug,' . $service->id,
            'short_description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'branch_id' => 'required|in:gulshan,bashundhara',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'required|integer',
        ]);

        $service->update($request->only([
            'title', 'slug', 'short_description', 'price', 'branch_id', 'status', 'sort_order',
        ]));

        return redirect('/admin/services')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect('/admin/services')->with('success', 'Service deleted.');
    }

    public function syncFromPriceList()
    {
        $categories = PriceListItem::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $count = 0;
        Service::query()->delete();

        foreach ($categories as $index => $cat) {
            $slug = Str::slug($cat);
            $items = PriceListItem::where('category', $cat)->get();
            $itemList = $items->pluck('name')->take(8)->implode(', ');
            $desc = $items->count() . ' treatments available including: ' . $itemList . '.';

            Service::create([
                'id' => $slug,
                'title' => $cat,
                'slug' => $slug,
                'short_description' => $desc,
                'price' => null,
                'branch_id' => 'gulshan',
                'status' => 'active',
                'sort_order' => $index,
            ]);
            $count++;
        }

        return redirect('/admin/services')->with('success', "Synced $count services from Price List categories.");
    }
}
