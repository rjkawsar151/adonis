<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FrontendController extends Controller
{
    public function services()
    {
        $branchId = request()->query('branch_id', 'all');

        $query = DB::table('services')->orderBy('id');

        if ($branchId !== 'all') {
            $query->where(function ($q) use ($branchId) {
                $q->where('branch_id', $branchId)
                  ->orWhere('branch_id', 'all')
                  ->orWhereNull('branch_id');
            });
        }

        return response()->json($query->get()->map(fn ($row) => [
            'id' => $row->id,
            'name' => $row->name,
            'description' => $row->description,
            'durationMin' => (int) $row->durationMin,
            'priceBDT' => (int) $row->priceBDT,
            'category' => $row->category,
            'icon' => $row->icon,
            'branch_id' => $row->branch_id,
            'price' => $row->price,
        ]));
    }

    public function barbers()
    {
        return response()->json(DB::table('barbers')->orderBy('id')->get()->map(fn ($row) => [
            'id' => $row->id,
            'name' => $row->name,
            'experienceYears' => (int) $row->experienceYears,
            'specialty' => $row->specialty,
            'portraitUrl' => $row->portraitUrl,
            'bio' => $row->bio,
            'rating' => (float) $row->rating,
        ]));
    }

    public function blogs()
    {
        return response()->json(DB::table('blogs')->where('status', 'published')->orderByDesc('createdAt')->get()->map(fn ($row) => [
            'id' => $row->id,
            'slug' => $row->slug,
            'title' => $row->title,
            'excerpt' => $row->excerpt,
            'coverImage' => $row->coverImage,
            'contentHtml' => $row->contentHtml,
            'seoTitle' => $row->seoTitle,
            'seoDescription' => $row->seoDescription,
            'status' => $row->status,
            'createdAt' => $row->createdAt,
            'updatedAt' => $row->updatedAt,
        ]));
    }

    public function priceList($branch = 'all')
    {
        $query = DB::table('price_list_items')->orderBy('sort_order')->orderBy('id');

        if ($branch !== 'all') {
            $query->where(function ($q) use ($branch) {
                $q->where('branch_id', $branch)
                  ->orWhere('branch_id', 'all')
                  ->orWhereNull('branch_id');
            });
        }

        $items = $query->get();
        $groups = [];

        foreach ($items as $item) {
            $cat = $item->category;
            if (!isset($groups[$cat])) {
                $groups[$cat] = ['category' => $cat, 'items' => []];
            }
            $groups[$cat]['items'][] = [
                'name' => $item->name,
                'price' => $item->price,
                'description' => $item->description,
            ];
        }

        return response()->json(array_values($groups));
    }
}
