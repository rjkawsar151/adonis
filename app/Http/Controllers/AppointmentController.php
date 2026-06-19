<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\WebsiteSetting;
use App\Notifications\AppointmentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        if ($request->has('email') && empty($request->email)) {
            $request->merge(['email' => null]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'regex:/^(?:\+?88)?01[3-9]\d{8}$/'
            ],
            'email' => 'nullable|email|max:255',
            'service_id' => 'nullable|string|max:255',
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

        $appointment = Appointment::create([
            'service_id' => $request->service_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $request->preferred_time,
            'note' => $request->note,
            'status' => 'pending',
            'branch_id' => $request->branch_id,
        ]);

        $settings = WebsiteSetting::first();
        $adminEmails = $settings->smtp_mail_to ?? 'info@adonis.com.bd';

        $this->configureMailer($settings);

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

    private function configureMailer($settings): void
    {
        // Purge cached transport so new config is used immediately
        Mail::purge('smtp');

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host'       => env('SMTP_HOST', $settings->smtp_host ?? ''),
            'mail.mailers.smtp.port'       => (int) env('SMTP_PORT', $settings->smtp_port ?? 587),
            'mail.mailers.smtp.encryption' => filter_var(env('SMTP_SECURE', $settings->smtp_encryption ?? false), FILTER_VALIDATE_BOOLEAN) ? 'ssl' : 'tls',
            'mail.mailers.smtp.username'   => env('SMTP_USER', $settings->smtp_username ?? ''),
            'mail.mailers.smtp.password'   => env('SMTP_PASS', $settings->smtp_password ?? ''),
            'mail.from.address'            => env('SMTP_FROM_EMAIL', $settings->smtp_mail_to ?? ''),
            'mail.from.name'               => 'Adonis Booking',
        ]);
    }
}
