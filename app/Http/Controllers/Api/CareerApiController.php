<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\CareerQuestion;
use App\Models\CareerApplication;
use App\Models\CareerApplicationAnswer;
use App\Mail\ApplicationSubmitted;
use App\Mail\NewApplicationNotification;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CareerApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Career::active()->with(['department', 'employmentType']);

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Filter by employment type
        if ($request->filled('employment_type_id')) {
            $query->where('employment_type_id', $request->employment_type_id);
        }

        $jobs = $query->latest()->paginate(6);

        // Fetch unique locations/depts/types for filters
        $locations = Career::active()->pluck('location')->unique()->filter()->values();
        $departments = \App\Models\Department::where('status', 'active')->orderBy('name')->get(['id', 'name']);
        $employmentTypes = \App\Models\EmploymentType::where('status', 'active')->orderBy('name')->get(['id', 'name']);

        return response()->json([
            'jobs' => $jobs,
            'filters' => [
                'locations' => $locations,
                'departments' => $departments,
                'employmentTypes' => $employmentTypes
            ]
        ]);
    }

    public function show($slug)
    {
        $job = Career::active()
            ->with(['department', 'employmentType', 'questions' => function($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }])
            ->where('slug', $slug)
            ->first();

        if (!$job) {
            return response()->json(['error' => 'Job listing not found.'], 404);
        }

        return response()->json($job);
    }

    public function apply(Request $request, $id)
    {
        $job = Career::active()->findOrFail($id);

        // Rate limiting logic
        $ip = $request->ip();
        $recentCount = CareerApplication::where('submitted_ip', $ip)
            ->where('created_at', '>=', now()->subHour())
            ->count();
        if ($recentCount >= 5) {
            return response()->json([
                'error' => 'Too many submissions. Please try again after an hour.'
            ], 429);
        }

        // Strict duplicate check
        $email = trim($request->input('email'));
        $phone = trim($request->input('phone'));
        $existing = CareerApplication::where('career_id', $job->id)
            ->where(function($q) use($email, $phone) {
                $q->where('email', $email)
                  ->orWhere('phone', $phone);
            })
            ->where('created_at', '>=', now()->subDays(30))
            ->first();

        if ($existing) {
            return response()->json([
                'error' => 'You have already submitted an application for this job posting recently.'
            ], 422);
        }

        // Standard validation
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:40',
            'present_address' => 'nullable|string',
            'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'current_company' => 'nullable|string|max:255',
            'current_designation' => 'nullable|string|max:255',
            'expected_salary' => 'nullable|numeric|min:0',
            'available_joining_date' => 'nullable|date',
            'cover_letter' => 'nullable|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // Default max 5MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Dynamic Questions validation
        $questions = $job->questions()->where('is_active', true)->get();
        $answersData = [];

        foreach ($questions as $q) {
            $inputKey = 'question_' . $q->id;
            $qAnswer = $request->input($inputKey);
            $fileAnswer = $request->file($inputKey);

            if ($q->is_required) {
                if ($q->question_type === 'file') {
                    if (!$request->hasFile($inputKey)) {
                        return response()->json([
                            'error' => "The question '{$q->question}' is mandatory."
                        ], 422);
                    }
                } else {
                    if ($qAnswer === null || $qAnswer === '') {
                        return response()->json([
                            'error' => "The question '{$q->question}' is mandatory."
                        ], 422);
                    }
                }
            }

            if ($q->question_type === 'file' && $request->hasFile($inputKey)) {
                $f = $request->file($inputKey);
                $ext = $f->getClientOriginalExtension();
                if (!in_array(strtolower($ext), ['pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg'])) {
                    return response()->json([
                        'error' => "Unsupported file type for question '{$q->question}'."
                    ], 422);
                }
                if ($f->getSize() > 5120 * 1024) {
                    return response()->json([
                        'error' => "File size exceeded (Max 5MB) for question '{$q->question}'."
                    ], 422);
                }
            }
        }

        // Upload CV
        if (!File::isDirectory(storage_path('app/resumes'))) {
            File::makeDirectory(storage_path('app/resumes'), 0755, true);
        }
        $cvFile = $request->file('cv');
        $cvFileName = 'cv_' . time() . '_' . uniqid() . '.' . $cvFile->getClientOriginalExtension();
        $cvFile->move(storage_path('app/resumes'), $cvFileName);
        $cvPath = 'resumes/' . $cvFileName;

        // Generate Reference Number (e.g. AD-2607-XXXX)
        $refNumber = 'AD-' . now()->format('ym') . '-' . strtoupper(Str::random(4));

        // Create application
        $application = CareerApplication::create([
            'career_id' => $job->id,
            'reference_number' => $refNumber,
            'full_name' => $request->full_name,
            'email' => $email,
            'phone' => $phone,
            'present_address' => $request->present_address,
            'linkedin_url' => $request->linkedin_url,
            'portfolio_url' => $request->portfolio_url,
            'current_company' => $request->current_company,
            'current_designation' => $request->current_designation,
            'expected_salary' => $request->expected_salary,
            'available_joining_date' => $request->available_joining_date,
            'cover_letter' => $request->cover_letter,
            'cv_path' => $cvPath,
            'status' => 'new',
            'submitted_ip' => $ip
        ]);

        // Save custom answers
        foreach ($questions as $q) {
            $inputKey = 'question_' . $q->id;
            $ansText = '';
            $qFilePath = null;

            if ($q->question_type === 'file') {
                if ($request->hasFile($inputKey)) {
                    if (!File::isDirectory(storage_path('app/resumes/custom_answers'))) {
                        File::makeDirectory(storage_path('app/resumes/custom_answers'), 0755, true);
                    }
                    $f = $request->file($inputKey);
                    $fName = 'ans_' . time() . '_' . uniqid() . '.' . $f->getClientOriginalExtension();
                    $f->move(storage_path('app/resumes/custom_answers'), $fName);
                    $qFilePath = 'resumes/custom_answers/' . $fName;
                    $ansText = 'File uploaded: ' . $fName;
                }
            } else {
                $ansVal = $request->input($inputKey);
                if (is_array($ansVal)) {
                    $ansText = implode(', ', $ansVal);
                } else {
                    $ansText = (string)$ansVal;
                }
            }

            CareerApplicationAnswer::create([
                'career_application_id' => $application->id,
                'career_question_id' => $q->id,
                'question_snapshot' => [
                    'question' => $q->question,
                    'question_type' => $q->question_type,
                    'options' => $q->options,
                    'is_required' => $q->is_required
                ],
                'answer' => $ansText,
                'file_path' => $qFilePath
            ]);
        }

        // Email notifications
        try {
            Mail::to($application->email)->send(new ApplicationSubmitted($application));
            
            // Notify Admin
            $settings = WebsiteSetting::first();
            $adminEmail = $settings ? $settings->smtp_mail_to : 'hr@adonis.com.bd';
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new NewApplicationNotification($application));
            }
        } catch (\Exception $e) {
            // Log mail failures but don't crash the submission response
            logger()->error('Career application email notify failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully.',
            'reference_number' => $refNumber
        ]);
    }
}
