<?php

namespace App\Mail;

use App\Models\CareerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewApplicationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(CareerApplication $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->subject('New Job Application - ' . $this->application->career->title)
                    ->view('emails.new_application_notification');
    }
}
