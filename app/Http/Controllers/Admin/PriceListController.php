<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceListItem;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
    public function index()
    {
        $categories = PriceListItem::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $grouped = [];
        foreach ($categories as $cat) {
            $grouped[$cat] = PriceListItem::where('category', $cat)
                ->orderBy('sort_order')
                ->get();
        }

        return view('admin.price-list.index', compact('grouped', 'categories'));
    }

    public function create()
    {
        $categories = PriceListItem::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $presetCategory = request('category', '');

        return view('admin.price-list.create', compact('categories', 'presetCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'nullable|string|max:255',
            'new_category' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:100',
            'description' => 'nullable|string',
            'branch_id' => 'nullable|string|max:60',
            'sort_order' => 'required|integer',
        ]);

        $data = $request->only([
            'name', 'price', 'description', 'branch_id', 'sort_order',
        ]);
        $data['category'] = $request->input('new_category') ?: $request->input('category');

        PriceListItem::create($data);

        return redirect('/admin/price-list')->with('success', 'Service added to price list.');
    }

    public function edit($id)
    {
        $item = PriceListItem::findOrFail($id);
        $categories = PriceListItem::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.price-list.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $item = PriceListItem::findOrFail($id);

        $request->validate([
            'category' => 'nullable|string|max:255',
            'new_category' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:100',
            'description' => 'nullable|string',
            'branch_id' => 'nullable|string|max:60',
            'sort_order' => 'required|integer',
        ]);

        $data = $request->only([
            'name', 'price', 'description', 'branch_id', 'sort_order',
        ]);
        $data['category'] = $request->input('new_category') ?: $request->input('category');

        $item->update($data);

        return redirect('/admin/price-list')->with('success', 'Price list item updated.');
    }

    public function destroy($id)
    {
        $item = PriceListItem::findOrFail($id);
        $item->delete();

        return redirect('/admin/price-list')->with('success', 'Price list item deleted.');
    }

    public function bulkUpdate(Request $request)
    {
        $rawIds = $request->input('ids', '');
        $ids = array_values(array_filter(array_map('trim', explode(',', $rawIds)), fn($v) => is_numeric($v)));

        if (empty($ids)) {
            return redirect('/admin/price-list')->with('error', 'No items selected.');
        }

        $request->merge(['ids' => $ids]);
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:price_list_items,id',
            'action' => 'required|in:delete,change_branch',
            'branch_id' => 'required_if:action,change_branch|in:gulshan,bashundhara,all',
        ]);
        $action = $request->input('action');

        if ($action === 'delete') {
            $count = PriceListItem::whereIn('id', $ids)->delete();
            return redirect('/admin/price-list')->with('success', "$count item(s) deleted.");
        }

        if ($action === 'change_branch') {
            $branch = $request->input('branch_id');
            $count = PriceListItem::whereIn('id', $ids)->update(['branch_id' => $branch]);
            return redirect('/admin/price-list')->with('success', "$count item(s) moved to " . ucfirst($branch) . ".");
        }

        return redirect('/admin/price-list');
    }
}
