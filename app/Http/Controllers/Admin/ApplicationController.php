<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerApplication;
use App\Models\CareerApplicationStatusHistory;
use App\Models\Career;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $careers = Career::orderBy('title')->get();
        $departments = Department::orderBy('name')->get();

        $query = CareerApplication::withTrashed()->with(['career.department']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('career_id')) {
            $query->where('career_id', $request->career_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->whereHas('career', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        if ($request->filled('location')) {
            $query->whereHas('career', function($q) use ($request) {
                $q->where('location', 'like', "%{$request->location}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'name') {
            $query->orderBy('full_name', 'asc');
        } elseif ($sort === 'job') {
            $query->whereHas('career', function($q) {
                $q->orderBy('title');
            });
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $applications = $query->paginate(15)->withQueryString();

        return view('admin.applications.index', compact('applications', 'careers', 'departments'));
    }

    public function show($id)
    {
        $application = CareerApplication::withTrashed()
            ->with(['career.department', 'answers.question', 'histories.user'])
            ->findOrFail($id);

        return view('admin.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, $id)
    {
        $application = CareerApplication::withTrashed()->findOrFail($id);

        $request->validate([
            'status' => 'required|string|in:new,under_review,shortlisted,interview_scheduled,selected,rejected,withdrawn',
            'note' => 'nullable|string'
        ]);

        $oldStatus = $application->status;
        $newStatus = $request->status;

        if ($oldStatus !== $newStatus) {
            $application->update(['status' => $newStatus]);

            CareerApplicationStatusHistory::create([
                'career_application_id' => $application->id,
                'previous_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => Auth::id(),
                'note' => $request->note ?? 'Status changed by admin.'
            ]);
        }

        return redirect()->back()->with('success', 'Application status updated.');
    }

    public function updateNotes(Request $request, $id)
    {
        $application = CareerApplication::withTrashed()->findOrFail($id);

        $request->validate([
            'admin_note' => 'nullable|string'
        ]);

        $application->update(['admin_note' => $request->admin_note]);

        return redirect()->back()->with('success', 'Admin note updated.');
    }

    public function downloadCv($id)
    {
        $application = CareerApplication::withTrashed()->findOrFail($id);
        $path = storage_path('app/' . $application->cv_path);

        if (!File::exists($path)) {
            // Check public path as fallback
            $path = public_path($application->cv_path);
            if (!File::exists($path)) {
                return redirect()->back()->with('error', 'CV file not found.');
            }
        }

        return response()->download($path, Str::slug($application->full_name) . '_resume.pdf');
    }

    public function viewCv($id)
    {
        $application = CareerApplication::withTrashed()->findOrFail($id);
        $path = storage_path('app/' . $application->cv_path);

        if (!File::exists($path)) {
            $path = public_path($application->cv_path);
            if (!File::exists($path)) {
                abort(404, 'CV file not found.');
            }
        }

        $contentType = 'application/pdf';
        $extension = File::extension($path);
        if ($extension === 'doc') {
            $contentType = 'application/msword';
        } elseif ($extension === 'docx') {
            $contentType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        }

        return response()->file($path, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);
    }

    public function destroy($id)
    {
        $application = CareerApplication::findOrFail($id);
        $application->delete();

        return redirect()->route('admin.applications.index')->with('success', 'Application soft-deleted successfully.');
    }

    public function restore($id)
    {
        $application = CareerApplication::withTrashed()->findOrFail($id);
        $application->restore();

        return redirect()->route('admin.applications.index')->with('success', 'Application restored successfully.');
    }

    public function forceDelete($id)
    {
        $application = CareerApplication::withTrashed()->findOrFail($id);
        $path = storage_path('app/' . $application->cv_path);
        if (File::exists($path)) {
            File::delete($path);
        }
        $application->forceDelete();

        return redirect()->route('admin.applications.index')->with('success', 'Application permanently deleted.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:career_applications,id',
            'action' => 'required|string|in:under_review,shortlist,reject,delete,restore,force_delete,download_cvs,export_csv'
        ]);

        $ids = $request->ids;
        $action = $request->action;

        if ($action === 'download_cvs') {
            return $this->downloadCvsZip($ids);
        }

        if ($action === 'export_csv') {
            return $this->exportCsv($ids);
        }

        foreach ($ids as $id) {
            $application = CareerApplication::withTrashed()->findOrFail($id);
            $oldStatus = $application->status;

            if ($action === 'under_review') {
                $application->update(['status' => 'under_review']);
                $this->logStatusChange($application->id, $oldStatus, 'under_review');
            } elseif ($action === 'shortlist') {
                $application->update(['status' => 'shortlisted']);
                $this->logStatusChange($application->id, $oldStatus, 'shortlisted');
            } elseif ($action === 'reject') {
                $application->update(['status' => 'rejected']);
                $this->logStatusChange($application->id, $oldStatus, 'rejected');
            } elseif ($action === 'delete') {
                $application->delete();
            } elseif ($action === 'restore') {
                $application->restore();
            } elseif ($action === 'force_delete') {
                $path = storage_path('app/' . $application->cv_path);
                if (File::exists($path)) {
                    File::delete($path);
                }
                $application->forceDelete();
            }
        }

        return redirect()->route('admin.applications.index')->with('success', 'Bulk action executed successfully.');
    }

    private function logStatusChange($appId, $oldStatus, $newStatus)
    {
        if ($oldStatus !== $newStatus) {
            CareerApplicationStatusHistory::create([
                'career_application_id' => $appId,
                'previous_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => Auth::id(),
                'note' => 'Status changed via bulk action.'
            ]);
        }
    }

    private function downloadCvsZip($ids)
    {
        $zip = new ZipArchive;
        $fileName = 'resumes_' . time() . '.zip';
        $zipPath = storage_path('app/temp/' . $fileName);

        if (!File::isDirectory(storage_path('app/temp'))) {
            File::makeDirectory(storage_path('app/temp'), 0755, true);
        }

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($ids as $id) {
                $application = CareerApplication::withTrashed()->findOrFail($id);
                $filePath = storage_path('app/' . $application->cv_path);

                if (!File::exists($filePath)) {
                    $filePath = public_path($application->cv_path);
                }

                if (File::exists($filePath)) {
                    // applicant-name_job-title_application-reference.pdf
                    $ext = File::extension($filePath) ?: 'pdf';
                    $zipName = Str::slug($application->full_name) . '_' . Str::slug($application->career->title) . '_' . $application->reference_number . '.' . $ext;
                    $zip->addFile($filePath, $zipName);
                }
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    private function exportCsv($ids)
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=applications_export_" . time() . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Reference Number', 'Applicant Name', 'Email', 'Phone', 'Job Title', 
            'Department', 'Status', 'Applied Date', 'Expected Salary', 'Joining Date',
            'LinkedIn', 'Portfolio', 'Current Company', 'Current Designation'
        ];

        $callback = function() use($ids, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($ids as $id) {
                $app = CareerApplication::withTrashed()->with(['career.department'])->findOrFail($id);
                fputcsv($file, [
                    $app->reference_number,
                    $app->full_name,
                    $app->email,
                    $app->phone,
                    $app->career->title,
                    $app->career->department ? $app->career->department->name : 'N/A',
                    strtoupper($app->status),
                    $app->created_at->format('Y-m-d'),
                    $app->expected_salary,
                    $app->available_joining_date ? $app->available_joining_date->format('Y-m-d') : 'N/A',
                    $app->linkedin_url,
                    $app->portfolio_url,
                    $app->current_company,
                    $app->current_designation
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
