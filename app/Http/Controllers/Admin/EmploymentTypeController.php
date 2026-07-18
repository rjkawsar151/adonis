<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmploymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmploymentTypeController extends Controller
{
    public function index()
    {
        $types = EmploymentType::withTrashed()->orderBy('name')->get();
        return view('admin.employment_types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:employment_types,name',
            'status' => 'required|in:active,inactive'
        ]);

        EmploymentType::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status
        ]);

        return redirect()->route('admin.employment-types.index')->with('success', 'Employment type created successfully.');
    }

    public function update(Request $request, $id)
    {
        $type = EmploymentType::withTrashed()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:employment_types,name,' . $id,
            'status' => 'required|in:active,inactive'
        ]);

        $type->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status
        ]);

        return redirect()->route('admin.employment-types.index')->with('success', 'Employment type updated successfully.');
    }

    public function destroy($id)
    {
        $type = EmploymentType::findOrFail($id);
        $type->delete();

        return redirect()->route('admin.employment-types.index')->with('success', 'Employment type soft-deleted successfully.');
    }

    public function restore($id)
    {
        $type = EmploymentType::withTrashed()->findOrFail($id);
        $type->restore();

        return redirect()->route('admin.employment-types.index')->with('success', 'Employment type restored successfully.');
    }

    public function forceDelete($id)
    {
        $type = EmploymentType::withTrashed()->findOrFail($id);
        $type->forceDelete();

        return redirect()->route('admin.employment-types.index')->with('success', 'Employment type permanently deleted.');
    }
}
