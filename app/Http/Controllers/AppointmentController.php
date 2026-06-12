<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\WebsiteSetting;
use App\Notifications\AppointmentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'regex:/^(?:\+?88)?01[3-9]\d{8}$/' // Bangladesh phone validation
            ],
            'email' => 'nullable|email|max:255',
            'service_id' => 'required|exists:services,id',
            'preferred_date' => 'required|date|after_or_equal:today',
            'preferred_time' => 'required|string|max:50',
            'note' => 'nullable|string|max:1000',
        ], [
            'phone.regex' => 'Please provide a valid Bangladesh mobile number (e.g., 01712345678).',
            'service_id.exists' => 'The selected treatment service does not exist.'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Create the appointment
        $appointment = Appointment::create([
            'service_id' => $request->service_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $request->preferred_time,
            'note' => $request->note,
            'status' => 'pending',
        ]);

        // Send Email to Admin(s) via SMTP
        $settings = WebsiteSetting::first();
        $adminEmails = $settings->smtp_mail_to ?? 'info@mayfair.com.bd';

        // Support comma-separated notification emails
        $notificationEmails = $settings->notification_emails
            ? array_map('trim', explode(',', $settings->notification_emails))
            : [$adminEmails];

        try {
            foreach ($notificationEmails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Notification::route('mail', $email)
                        ->notify(new AppointmentNotification($appointment, 'admin'));
                }
            }

            // Send Confirmation to Patient if email provided
            if ($appointment->email) {
                Notification::route('mail', $appointment->email)
                    ->notify(new AppointmentNotification($appointment, 'patient'));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMTP Mail Sending failed: ' . $e->getMessage());
        }

        $msg = 'Your appointment request has been submitted successfully! Our team will contact you shortly.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $msg,
                'appointment' => $appointment
            ], 200);
        }

        return back()->with('success', $msg);
    }
}
