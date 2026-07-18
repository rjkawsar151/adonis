<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\WebsiteSetting;
use App\Notifications\AppointmentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with('service')->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by name, phone, or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $appointments = $query->paginate(15);

        return view('admin.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load('service');
        return view('admin.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,contacted,confirmed,completed,cancelled',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $appointment->status;

        $appointment->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        // Send notification to client if status changed and email exists
        if ($oldStatus !== $request->status && $appointment->email) {
            try {
                $settings = WebsiteSetting::first();
                $this->configureMailer($settings);
                Notification::route('mail', $appointment->email)
                    ->notify(new AppointmentNotification($appointment, 'status_update'));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('SMTP Status Update Mail Sending failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Appointment status updated.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect('/admin/appointments')->with('success', 'Appointment deleted.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:appointments,id',
            'action' => 'required|string|in:pending,contacted,confirmed,completed,cancelled,delete'
        ]);

        $ids = $request->ids;
        $action = $request->action;

        if ($action === 'delete') {
            Appointment::whereIn('id', $ids)->delete();
        } else {
            // Retrieve appointments to update status and send emails
            $appointments = Appointment::whereIn('id', $ids)->get();
            foreach ($appointments as $appt) {
                $oldStatus = $appt->status;
                if ($oldStatus !== $action) {
                    $appt->update(['status' => $action]);
                    
                    if ($appt->email) {
                        try {
                            $settings = WebsiteSetting::first();
                            $this->configureMailer($settings);
                            Notification::route('mail', $appt->email)
                                ->notify(new AppointmentNotification($appt, 'status_update'));
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('SMTP Bulk Status Update Mail Sending failed: ' . $e->getMessage());
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.appointments.index')->with('success', 'Bulk action executed successfully.');
    }

    private function configureMailer($settings): void
    {
        Mail::purge('smtp');

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host'       => env('SMTP_HOST', $settings->smtp_host ?? ''),
            'mail.mailers.smtp.port'       => (int) env('SMTP_PORT', $settings->smtp_port ?? 587),
            'mail.mailers.smtp.encryption' => filter_var(env('SMTP_SECURE', $settings->smtp_encryption ?? false), FILTER_VALIDATE_BOOLEAN) ? 'ssl' : 'tls',
            'mail.mailers.smtp.username'   => env('SMTP_USER', $settings->smtp_username ?? ''),
            'mail.mailers.smtp.password'   => env('SMTP_PASS', $settings->smtp_password ?? ''),
            'mail.from.address'            => env('SMTP_FROM_EMAIL', $settings->smtp_mail_to ?? ''),
            'mail.from.name'               => "Adonis men's Grooming Salon",
        ]);
    }
}
