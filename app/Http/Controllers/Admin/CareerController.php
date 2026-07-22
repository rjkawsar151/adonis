<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\CareerQuestion;
use App\Models\Department;
use App\Models\EmploymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CareerController extends Controller
{
    public function index()
    {
        $careers = Career::withTrashed()
            ->with(['department', 'employmentType'])
            ->withCount(['applications', 'applications as shortlisted_count' => function($q) {
                $q->where('status', 'shortlisted');
            }, 'applications as rejected_count' => function($q) {
                $q->where('status', 'rejected');
            }])
            ->latest()
            ->get();

        return view('admin.careers.index', compact('careers'));
    }

    public function create()
    {
        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $employmentTypes = EmploymentType::where('status', 'active')->orderBy('name')->get();
        return view('admin.careers.create', compact('departments', 'employmentTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:careers,slug',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'educational_requirements' => 'nullable|string',
            'experience_requirements' => 'nullable|string',
            'additional_requirements' => 'nullable|string',
            'skills' => 'nullable|string',
            'benefits' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'employment_type_id' => 'nullable|exists:employment_types,id',
            'location' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Both',
            'vacancy' => 'required|integer|min:1',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_type' => 'required|string|max:40',
            'application_deadline' => 'nullable|date',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'required|in:draft,active,inactive,closed',
            'is_featured' => 'boolean',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'show_address' => 'boolean',
            'show_linkedin' => 'boolean',
            'show_portfolio' => 'boolean',
            'show_current_company' => 'boolean',
            'show_current_designation' => 'boolean',
            'show_expected_salary' => 'boolean',
            'show_joining_date' => 'boolean',
            'show_cover_letter' => 'boolean',
        ]);

        $featuredImageUrl = null;
        if ($request->hasFile('featured_image')) {
            if (!File::isDirectory(public_path('uploads/careers'))) {
                File::makeDirectory(public_path('uploads/careers'), 0755, true);
            }
            $file = $request->file('featured_image');
            $fileName = 'job_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/careers'), $fileName);
            $featuredImageUrl = 'uploads/careers/' . $fileName;
        }

        $career = Career::create([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'responsibilities' => $request->responsibilities,
            'educational_requirements' => $request->educational_requirements,
            'experience_requirements' => $request->experience_requirements,
            'additional_requirements' => $request->additional_requirements,
            'skills' => $request->skills,
            'benefits' => $request->benefits,
            'department_id' => $request->department_id,
            'employment_type_id' => $request->employment_type_id,
            'location' => $request->location,
            'gender' => $request->gender,
            'vacancy' => $request->vacancy,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'salary_type' => $request->salary_type,
            'application_deadline' => $request->application_deadline,
            'featured_image' => $featuredImageUrl,
            'status' => $request->status,
            'is_featured' => $request->has('is_featured') ? true : false,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'created_by' => Auth::id(),
            'show_address' => $request->has('show_address') ? true : false,
            'show_linkedin' => $request->has('show_linkedin') ? true : false,
            'show_portfolio' => $request->has('show_portfolio') ? true : false,
            'show_current_company' => $request->has('show_current_company') ? true : false,
            'show_current_designation' => $request->has('show_current_designation') ? true : false,
            'show_expected_salary' => $request->has('show_expected_salary') ? true : false,
            'show_joining_date' => $request->has('show_joining_date') ? true : false,
            'show_cover_letter' => $request->has('show_cover_letter') ? true : false,
        ]);

        // Process Custom Questions
        if ($request->has('questions')) {
            foreach ($request->questions as $index => $q) {
                if (empty($q['question'])) continue;

                $optionsArray = null;
                if (!empty($q['options'])) {
                    $optionsArray = array_map('trim', explode(',', $q['options']));
                }

                CareerQuestion::create([
                    'career_id' => $career->id,
                    'question' => $q['question'],
                    'help_text' => $q['help_text'] ?? null,
                    'question_type' => $q['question_type'],
                    'options' => $optionsArray,
                    'is_required' => isset($q['is_required']) ? true : false,
                    'is_active' => true,
                    'sort_order' => $q['sort_order'] ?? $index
                ]);
            }
        }

        return redirect()->route('admin.careers.index')->with('success', 'Job posting created successfully.');
    }

    public function show($id)
    {
        $career = Career::withTrashed()
            ->with(['department', 'employmentType', 'questions', 'applications' => function($q) {
                $q->withTrashed()->latest();
            }])
            ->withCount(['applications', 'applications as shortlisted_count' => function($q) {
                $q->where('status', 'shortlisted');
            }, 'applications as rejected_count' => function($q) {
                $q->where('status', 'rejected');
            }])
            ->findOrFail($id);

        return view('admin.careers.show', compact('career'));
    }

    public function edit($id)
    {
        $career = Career::withTrashed()->with('questions')->findOrFail($id);
        $departments = Department::where('status', 'active')->orderBy('name')->get();
        $employmentTypes = EmploymentType::where('status', 'active')->orderBy('name')->get();
        return view('admin.careers.edit', compact('career', 'departments', 'employmentTypes'));
    }

    public function update(Request $request, $id)
    {
        $career = Career::withTrashed()->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:careers,slug,' . $id,
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'educational_requirements' => 'nullable|string',
            'experience_requirements' => 'nullable|string',
            'additional_requirements' => 'nullable|string',
            'skills' => 'nullable|string',
            'benefits' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'employment_type_id' => 'nullable|exists:employment_types,id',
            'location' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Both',
            'vacancy' => 'required|integer|min:1',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_type' => 'required|string|max:40',
            'application_deadline' => 'nullable|date',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'required|in:draft,active,inactive,closed',
            'is_featured' => 'boolean',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'show_address' => 'boolean',
            'show_linkedin' => 'boolean',
            'show_portfolio' => 'boolean',
            'show_current_company' => 'boolean',
            'show_current_designation' => 'boolean',
            'show_expected_salary' => 'boolean',
            'show_joining_date' => 'boolean',
            'show_cover_letter' => 'boolean',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'responsibilities' => $request->responsibilities,
            'educational_requirements' => $request->educational_requirements,
            'experience_requirements' => $request->experience_requirements,
            'additional_requirements' => $request->additional_requirements,
            'skills' => $request->skills,
            'benefits' => $request->benefits,
            'department_id' => $request->department_id,
            'employment_type_id' => $request->employment_type_id,
            'location' => $request->location,
            'gender' => $request->gender,
            'vacancy' => $request->vacancy,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'salary_type' => $request->salary_type,
            'application_deadline' => $request->application_deadline,
            'status' => $request->status,
            'is_featured' => $request->has('is_featured') ? true : false,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'updated_by' => Auth::id(),
            'show_address' => $request->has('show_address') ? true : false,
            'show_linkedin' => $request->has('show_linkedin') ? true : false,
            'show_portfolio' => $request->has('show_portfolio') ? true : false,
            'show_current_company' => $request->has('show_current_company') ? true : false,
            'show_current_designation' => $request->has('show_current_designation') ? true : false,
            'show_expected_salary' => $request->has('show_expected_salary') ? true : false,
            'show_joining_date' => $request->has('show_joining_date') ? true : false,
            'show_cover_letter' => $request->has('show_cover_letter') ? true : false,
        ];

        if ($request->hasFile('featured_image')) {
            if (!File::isDirectory(public_path('uploads/careers'))) {
                File::makeDirectory(public_path('uploads/careers'), 0755, true);
            }
            if ($career->featured_image && File::exists(public_path($career->featured_image))) {
                File::delete(public_path($career->featured_image));
            }
            $file = $request->file('featured_image');
            $fileName = 'job_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/careers'), $fileName);
            $data['featured_image'] = 'uploads/careers/' . $fileName;
        }

        $career->update($data);

        // Sync Custom Questions
        // delete existing questions first, since we are doing a complete reload
        // (but note: this keeps references in career_application_answers safe because answers have question snapshots)
        $career->questions()->delete();

        if ($request->has('questions')) {
            foreach ($request->questions as $index => $q) {
                if (empty($q['question'])) continue;

                $optionsArray = null;
                if (!empty($q['options'])) {
                    $optionsArray = is_array($q['options']) ? $q['options'] : array_map('trim', explode(',', $q['options']));
                }

                CareerQuestion::create([
                    'career_id' => $career->id,
                    'question' => $q['question'],
                    'help_text' => $q['help_text'] ?? null,
                    'question_type' => $q['question_type'],
                    'options' => $optionsArray,
                    'is_required' => isset($q['is_required']) ? true : false,
                    'is_active' => true,
                    'sort_order' => $q['sort_order'] ?? $index
                ]);
            }
        }

        return redirect()->route('admin.careers.index')->with('success', 'Job posting updated successfully.');
    }

    public function destroy($id)
    {
        $career = Career::findOrFail($id);
        $career->delete();

        return redirect()->route('admin.careers.index')->with('success', 'Job posting soft-deleted successfully.');
    }

    public function restore($id)
    {
        $career = Career::withTrashed()->findOrFail($id);
        $career->restore();

        return redirect()->route('admin.careers.index')->with('success', 'Job posting restored successfully.');
    }

    public function forceDelete($id)
    {
        $career = Career::withTrashed()->findOrFail($id);
        if ($career->featured_image && File::exists(public_path($career->featured_image))) {
            File::delete(public_path($career->featured_image));
        }
        $career->forceDelete();

        return redirect()->route('admin.careers.index')->with('success', 'Job posting permanently deleted.');
    }
}
