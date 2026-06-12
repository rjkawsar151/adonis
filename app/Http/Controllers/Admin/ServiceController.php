<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('sort_order')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug',
            'short_description' => 'nullable|string',
            'main_description' => 'nullable|string',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:255',
            'cause_title' => 'nullable|string|max:255',
            'cause_description' => 'nullable|string',
            'treatment_title' => 'nullable|string|max:255',
            'treatment_description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'required|integer',
            'is_featured' => 'nullable|boolean',
            'show_in_sidebar' => 'nullable|boolean',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'cause_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'treatment_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'steps' => 'nullable|array',
            'steps.*.title' => 'nullable|string|max:255',
            'steps.*.description' => 'nullable|string',
            'steps.*.status' => 'nullable|in:active,inactive',
        ]);

        $data = $request->except(['cause_bullets', 'treatment_bullets', 'steps', 'hero_image', 'main_image', 'cause_image', 'treatment_image']);
        $data['is_featured'] = $request->has('is_featured');
        $data['show_in_sidebar'] = $request->has('show_in_sidebar');

        // Handle Image Uploads
        $images = ['hero_image', 'main_image', 'cause_image', 'treatment_image'];
        foreach ($images as $imgKey) {
            if ($request->hasFile($imgKey)) {
                $this->ensureUploadDirectory();

                $file = $request->file($imgKey);
                $fileName = time() . '_' . $imgKey . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/services'), $fileName);
                $data[$imgKey] = 'uploads/services/' . $fileName;
            }
        }

        $service = Service::create($data);

        // Sync bullets
        $this->syncBullets($service, 'cause', $request->input('cause_bullets', []));
        $this->syncBullets($service, 'treatment', $request->input('treatment_bullets', []));
        $this->syncSteps($service, $request->input('steps', []));

        return redirect('/admin/services')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $causeBullets = $service->causeBullets()->get();
        $treatmentBullets = $service->treatmentBullets()->get();
        $steps = $service->steps()->get();
        return view('admin.services.edit', compact('service', 'causeBullets', 'treatmentBullets', 'steps'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug,' . $service->id,
            'short_description' => 'nullable|string',
            'main_description' => 'nullable|string',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:255',
            'cause_title' => 'nullable|string|max:255',
            'cause_description' => 'nullable|string',
            'treatment_title' => 'nullable|string|max:255',
            'treatment_description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'required|integer',
            'is_featured' => 'nullable|boolean',
            'show_in_sidebar' => 'nullable|boolean',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'cause_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'treatment_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'steps' => 'nullable|array',
            'steps.*.title' => 'nullable|string|max:255',
            'steps.*.description' => 'nullable|string',
            'steps.*.status' => 'nullable|in:active,inactive',
        ]);

        $data = $request->except(['cause_bullets', 'treatment_bullets', 'steps', 'hero_image', 'main_image', 'cause_image', 'treatment_image']);
        $data['is_featured'] = $request->has('is_featured');
        $data['show_in_sidebar'] = $request->has('show_in_sidebar');

        // Handle Image Uploads
        $images = ['hero_image', 'main_image', 'cause_image', 'treatment_image'];
        foreach ($images as $imgKey) {
            if ($request->hasFile($imgKey)) {
                $this->ensureUploadDirectory();

                // Delete old image file
                if ($service->$imgKey && File::exists(public_path($service->$imgKey))) {
                    File::delete(public_path($service->$imgKey));
                }

                $file = $request->file($imgKey);
                $fileName = time() . '_' . $imgKey . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/services'), $fileName);
                $data[$imgKey] = 'uploads/services/' . $fileName;
            }
        }

        $service->update($data);

        // Sync bullets
        $this->syncBullets($service, 'cause', $request->input('cause_bullets', []));
        $this->syncBullets($service, 'treatment', $request->input('treatment_bullets', []));
        $this->syncSteps($service, $request->input('steps', []));

        return redirect('/admin/services')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        // Delete image files
        $images = ['hero_image', 'main_image', 'cause_image', 'treatment_image'];
        foreach ($images as $imgKey) {
            if ($service->$imgKey && File::exists(public_path($service->$imgKey))) {
                File::delete(public_path($service->$imgKey));
            }
        }

        $service->delete();

        return redirect('/admin/services')->with('success', 'Service deleted successfully.');
    }

    protected function syncBullets(Service $service, $type, array $bulletTexts)
    {
        $service->bullets()->where('section_type', $type)->delete();

        foreach ($bulletTexts as $index => $text) {
            if (!empty(trim($text))) {
                $service->bullets()->create([
                    'section_type' => $type,
                    'bullet_text' => trim($text),
                    'sort_order' => $index,
                ]);
            }
        }
    }

    protected function syncSteps(Service $service, array $steps)
    {
        $service->steps()->delete();

        $stepNumber = 1;
        foreach ($steps as $index => $step) {
            $title = trim($step['title'] ?? '');
            $description = trim($step['description'] ?? '');

            if ($title === '' && $description === '') {
                continue;
            }

            $service->steps()->create([
                'step_number' => $stepNumber++,
                'title' => $title,
                'description' => $description,
                'sort_order' => $index,
                'status' => $step['status'] ?? 'active',
            ]);
        }
    }

    protected function ensureUploadDirectory()
    {
        if (!File::isDirectory(public_path('uploads/services'))) {
            File::makeDirectory(public_path('uploads/services'), 0755, true);
        }
    }
}
