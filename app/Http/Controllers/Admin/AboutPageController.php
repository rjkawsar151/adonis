<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutChairmanMessage;
use App\Models\AboutMdMessage;
use App\Models\AboutCompanyIntroduction;
use App\Models\AboutMissionVision;
use App\Models\AboutCoreValue;
use App\Models\AboutWhyChooseUs;
use App\Models\AboutStatistic;
use App\Models\AboutTimeline;
use App\Models\AboutTeamMember;
use App\Models\AboutCta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use App\Services\ImageCompressor;

class AboutPageController extends Controller
{
    public function index()
    {
        $chairmanMessage = AboutChairmanMessage::first() ?? new AboutChairmanMessage();
        $mdMessage = AboutMdMessage::first() ?? new AboutMdMessage();
        $companyIntro = AboutCompanyIntroduction::first() ?? new AboutCompanyIntroduction();
        $cta = AboutCta::first() ?? new AboutCta();

        $missionsVisions = AboutMissionVision::orderBy('sort_order')->get();
        $coreValues = AboutCoreValue::orderBy('sort_order')->get();
        $whyChooseUs = AboutWhyChooseUs::orderBy('sort_order')->get();
        $statistics = AboutStatistic::orderBy('sort_order')->get();
        $timelines = AboutTimeline::orderBy('sort_order')->get();
        $teamMembers = AboutTeamMember::orderBy('sort_order')->get();

        return view('admin.about-page.index', compact(
            'chairmanMessage',
            'mdMessage',
            'companyIntro',
            'cta',
            'missionsVisions',
            'coreValues',
            'whyChooseUs',
            'statistics',
            'timelines',
            'teamMembers'
        ));
    }

    public function saveChairmanMessage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'speech' => 'required|string',
            'quotation' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'signature_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'is_active' => 'boolean',
        ]);

        $chairman = AboutChairmanMessage::first() ?? new AboutChairmanMessage();

        $data = $request->except(['photo', 'signature_image']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('photo')) {
            if ($chairman->photo) {
                $this->removeOldImage($chairman->photo);
            }
            $data['photo'] = $this->uploadImage($request->file('photo'), 'about');
        }

        if ($request->hasFile('signature_image')) {
            if ($chairman->signature_image) {
                $this->removeOldImage($chairman->signature_image);
            }
            $data['signature_image'] = $this->uploadImage($request->file('signature_image'), 'about');
        }

        $chairman->fill($data);
        $chairman->save();

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Chairman Message saved successfully.');
    }

    public function saveMdMessage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'speech' => 'required|string',
            'quotation' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'signature_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'is_active' => 'boolean',
        ]);

        $md = AboutMdMessage::first() ?? new AboutMdMessage();

        $data = $request->except(['photo', 'signature_image']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('photo')) {
            if ($md->photo) {
                $this->removeOldImage($md->photo);
            }
            $data['photo'] = $this->uploadImage($request->file('photo'), 'about');
        }

        if ($request->hasFile('signature_image')) {
            if ($md->signature_image) {
                $this->removeOldImage($md->signature_image);
            }
            $data['signature_image'] = $this->uploadImage($request->file('signature_image'), 'about');
        }

        $md->fill($data);
        $md->save();

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'MD Message saved successfully.');
    }

    public function saveCompanyIntro(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360',
            'is_active' => 'boolean',
        ], [
            'featured_image.uploaded' => 'The featured image could not be uploaded. Please use a JPG, PNG, GIF, or WebP image smaller than 15 MB.',
            'featured_image.image' => 'The featured image must be a valid image file.',
            'featured_image.mimes' => 'The featured image must be a JPG, PNG, GIF, or WebP file.',
            'featured_image.max' => 'The featured image must not be larger than 15 MB.',
        ]);

        $intro = AboutCompanyIntroduction::first() ?? new AboutCompanyIntroduction();

        $data = $request->except(['featured_image']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('featured_image')) {
            $oldImage = $intro->featured_image;
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'), 'about');

            if ($oldImage) {
                $this->removeOldImage($oldImage);
            }
        }

        $intro->fill($data);
        $intro->save();

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Company Introduction saved successfully.');
    }

    public function saveCta(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'primary_button_text' => 'required|string|max:255',
            'primary_button_url' => 'required|string|max:255',
            'secondary_button_text' => 'required|string|max:255',
            'secondary_button_url' => 'required|string|max:255',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'is_active' => 'boolean',
        ]);

        $cta = AboutCta::first() ?? new AboutCta();

        $data = $request->except(['background_image']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('background_image')) {
            if ($cta->background_image) {
                $this->removeOldImage($cta->background_image);
            }
            $data['background_image'] = $this->uploadImage($request->file('background_image'), 'about');
        }

        $cta->fill($data);
        $cta->save();

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'About Page CTA saved successfully.');
    }

    // ─── Mission & Vision CRUD ───
    public function storeMissionVision(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:mission,vision',
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'content' => 'required|string',
            'icon_or_image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = AboutMissionVision::count();

        AboutMissionVision::create($data);

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Mission/Vision added successfully.');
    }

    public function updateMissionVision(Request $request, $id)
    {
        $item = AboutMissionVision::findOrFail($id);

        $request->validate([
            'type' => 'required|string|in:mission,vision',
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'content' => 'required|string',
            'icon_or_image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $item->update($data);

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Mission/Vision updated successfully.');
    }

    public function destroyMissionVision($id)
    {
        $item = AboutMissionVision::findOrFail($id);
        $item->delete();

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Mission/Vision deleted successfully.');
    }

    // ─── Core Values CRUD ───
    public function storeCoreValue(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = AboutCoreValue::count();

        AboutCoreValue::create($data);

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Core Value added successfully.');
    }

    public function updateCoreValue(Request $request, $id)
    {
        $item = AboutCoreValue::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $item->update($data);

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Core Value updated successfully.');
    }

    public function destroyCoreValue($id)
    {
        $item = AboutCoreValue::findOrFail($id);
        $item->delete();

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Core Value deleted successfully.');
    }

    // ─── Why Choose Us CRUD ───
    public function storeWhyChooseUs(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = AboutWhyChooseUs::count();

        AboutWhyChooseUs::create($data);

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Point added successfully.');
    }

    public function updateWhyChooseUs(Request $request, $id)
    {
        $item = AboutWhyChooseUs::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $item->update($data);

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Point updated successfully.');
    }

    public function destroyWhyChooseUs($id)
    {
        $item = AboutWhyChooseUs::findOrFail($id);
        $item->delete();

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Point deleted successfully.');
    }

    // ─── Statistics CRUD ───
    public function storeStatistic(Request $request)
    {
        $request->validate([
            'counter_number' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'label' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = AboutStatistic::count();

        AboutStatistic::create($data);

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Statistic added successfully.');
    }

    public function updateStatistic(Request $request, $id)
    {
        $item = AboutStatistic::findOrFail($id);

        $request->validate([
            'counter_number' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'label' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $item->update($data);

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Statistic updated successfully.');
    }

    public function destroyStatistic($id)
    {
        $item = AboutStatistic::findOrFail($id);
        $item->delete();

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Statistic deleted successfully.');
    }

    // ─── Timeline CRUD ───
    public function storeTimeline(Request $request)
    {
        $request->validate([
            'year_or_date' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->except(['image']);
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = AboutTimeline::count();

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'about');
        }

        AboutTimeline::create($data);

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Timeline milestone added successfully.');
    }

    public function updateTimeline(Request $request, $id)
    {
        $item = AboutTimeline::findOrFail($id);

        $request->validate([
            'year_or_date' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->except(['image']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($item->image) {
                $this->removeOldImage($item->image);
            }
            $data['image'] = $this->uploadImage($request->file('image'), 'about');
        }

        $item->update($data);

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Timeline milestone updated successfully.');
    }

    public function destroyTimeline($id)
    {
        $item = AboutTimeline::findOrFail($id);
        if ($item->image) {
            $this->removeOldImage($item->image);
        }
        $item->delete();

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Timeline milestone deleted successfully.');
    }

    // ─── Team Members CRUD ───
    public function storeTeamMember(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360',
            'biography' => 'nullable|string',
            'facebook_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $data = $request->except(['photo']);
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = AboutTeamMember::count();

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->uploadImage($request->file('photo'), 'about');
        }

        AboutTeamMember::create($data);

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Team Member added successfully.');
    }

    public function updateTeamMember(Request $request, $id)
    {
        $item = AboutTeamMember::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360',
            'biography' => 'nullable|string',
            'facebook_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $data = $request->except(['photo']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('photo')) {
            if ($item->photo) {
                $this->removeOldImage($item->photo);
            }
            $data['photo'] = $this->uploadImage($request->file('photo'), 'about');
        }

        $item->update($data);

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Team Member updated successfully.');
    }

    public function destroyTeamMember($id)
    {
        $item = AboutTeamMember::findOrFail($id);
        if ($item->photo) {
            $this->removeOldImage($item->photo);
        }
        $item->delete();

        Cache::forget('adonis_about_page_data');

        return redirect()->back()->with('success', 'Team Member deleted successfully.');
    }

    // ─── Drag & Drop Reordering ───
    public function reorder(Request $request)
    {
        $request->validate([
            'model' => 'required|string|in:missions_visions,core_values,why_choose_us,statistics,timelines,team_members',
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $modelMap = [
            'missions_visions' => \App\Models\AboutMissionVision::class,
            'core_values' => \App\Models\AboutCoreValue::class,
            'why_choose_us' => \App\Models\AboutWhyChooseUs::class,
            'statistics' => \App\Models\AboutStatistic::class,
            'timelines' => \App\Models\AboutTimeline::class,
            'team_members' => \App\Models\AboutTeamMember::class,
        ];

        $modelClass = $modelMap[$request->model];

        foreach ($request->ids as $index => $id) {
            $modelClass::where('id', $id)->update(['sort_order' => $index]);
        }

        Cache::forget('adonis_about_page_data');

        return response()->json(['success' => true]);
    }

    // ─── Image Helpers ───
    private function uploadImage($file, $folder)
    {
        return ImageCompressor::compressAndSaveWebp($file, 'uploads/' . $folder, 70);
    }

    private function removeOldImage($path)
    {
        $fullPath = public_path($path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
