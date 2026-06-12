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
    protected $recipientType; // 'admin' or 'patient'

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($appointment, $recipientType = 'admin')
    {
        $this->appointment = $appointment;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $serviceName = $this->appointment->service ? $this->appointment->service->title : "Men's Health";

        if ($this->recipientType === 'admin') {
            return (new MailMessage)
                ->subject("New Appointment Request - Mayfair Men's Health")
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
                ->line('Mayfair Wellness Clinic Website');
        } else {
            return (new MailMessage)
                ->subject("Appointment Request Received - Mayfair Wellness Clinic")
                ->greeting("Dear {$this->appointment->name},")
                ->line("Thank you for booking an appointment with Mayfair Wellness Clinic. Our team has received your request and will contact you shortly to confirm your slot.")
                ->line("Preferred Time: {$this->appointment->preferred_date} at {$this->appointment->preferred_time}")
                ->line("Support Phone: +8801986-660000")
                ->line("Regards,")
                ->line("Mayfair Wellness Clinic");
        }
    }
}
