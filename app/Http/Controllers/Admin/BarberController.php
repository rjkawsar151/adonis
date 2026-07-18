<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BarberController extends Controller
{
    public function index()
    {
        $barbers = Barber::orderBy('name')->get();
        return view('admin.barbers.index', compact('barbers'));
    }

    public function create()
    {
        return view('admin.barbers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'experienceYears' => 'nullable|integer|min:0',
            'portrait' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'bio' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        $portraitUrl = '';
        if ($request->hasFile('portrait')) {
            if (!File::isDirectory(public_path('uploads/barbers'))) {
                File::makeDirectory(public_path('uploads/barbers'), 0755, true);
            }
            $file = $request->file('portrait');
            $fileName = 'barber_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/barbers'), $fileName);
            $portraitUrl = 'uploads/barbers/' . $fileName;
        }

        Barber::create([
            'id' => Str::slug($request->name) . '-' . time(),
            'name' => $request->name,
            'experienceYears' => $request->experienceYears ?? 0,
            'specialty' => $request->specialty ?? '',
            'portraitUrl' => $portraitUrl,
            'bio' => $request->bio ?? '',
            'rating' => $request->rating ?? 5.0,
        ]);

        return redirect('/admin/barbers')->with('success', 'Barber added.');
    }

    public function edit($id)
    {
        $barber = Barber::findOrFail($id);
        return view('admin.barbers.edit', compact('barber'));
    }

    public function update(Request $request, $id)
    {
        $barber = Barber::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'experienceYears' => 'nullable|integer|min:0',
            'portrait' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'bio' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        $data = [
            'name' => $request->name,
            'experienceYears' => $request->experienceYears ?? 0,
            'specialty' => $request->specialty ?? '',
            'bio' => $request->bio ?? '',
            'rating' => $request->rating ?? 5.0,
        ];

        if ($request->hasFile('portrait')) {
            if (!File::isDirectory(public_path('uploads/barbers'))) {
                File::makeDirectory(public_path('uploads/barbers'), 0755, true);
            }
            if ($barber->portraitUrl && File::exists(public_path($barber->portraitUrl))) {
                File::delete(public_path($barber->portraitUrl));
            }
            $file = $request->file('portrait');
            $fileName = 'barber_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/barbers'), $fileName);
            $data['portraitUrl'] = 'uploads/barbers/' . $fileName;
        }

        $barber->update($data);

        return redirect('/admin/barbers')->with('success', 'Barber updated.');
    }

    public function destroy($id)
    {
        $barber = Barber::findOrFail($id);
        if ($barber->portraitUrl && File::exists(public_path($barber->portraitUrl))) {
            File::delete(public_path($barber->portraitUrl));
        }
        $barber->delete();
        return redirect('/admin/barbers')->with('success', 'Barber deleted.');
    }
}
