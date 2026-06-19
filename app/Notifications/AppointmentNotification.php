<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentNotification extends Notification
{
    use Queueable;

    protected $appointment;
    protected $recipientType;

    public function __construct($appointment, $recipientType = 'admin')
    {
        $this->appointment = $appointment;
        $this->recipientType = $recipientType;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $serviceName = $this->appointment->service ? $this->appointment->service->title : 'General';

        if ($this->recipientType === 'admin') {
            return (new MailMessage)
                ->subject("New Appointment Request - Adonis Men's Grooming")
                ->greeting("Hello Admin,")
                ->line("A new appointment request has been received on the website.")
                ->line("Name: {$this->appointment->name}")
                ->line("Phone: {$this->appointment->phone}")
                ->line("Email: " . ($this->appointment->email ?? 'Not Provided'))
                ->line("Service: {$serviceName}")
                ->line("Preferred Date: {$this->appointment->preferred_date}")
                ->line("Preferred Time: {$this->appointment->preferred_time}")
                ->line("Note: " . ($this->appointment->note ?? 'None'))
                ->action('View Appointment in Admin Panel', url('/admin/appointments'))
                ->line('Regards,')
                ->line("Adonis Men's Grooming");
        } else {
            return (new MailMessage)
                ->subject("Appointment Request Received - Adonis Men's Grooming")
                ->greeting("Dear {$this->appointment->name},")
                ->line("Thank you for booking an appointment with Adonis Men's Grooming. Our team has received your request and will contact you shortly to confirm your slot.")
                ->line("Preferred Time: {$this->appointment->preferred_date} at {$this->appointment->preferred_time}")
                ->line("Support Phone: +880 1919-700800")
                ->line("Regards,")
                ->line("Adonis Men's Grooming");
        }
    }
}
