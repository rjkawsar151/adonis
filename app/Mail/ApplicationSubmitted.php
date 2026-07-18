<?php

namespace App\Mail;

use App\Models\CareerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(CareerApplication $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->subject('Application Received - Reference #' . $this->application->reference_number)
                    ->view('emails.application_submitted');
    }
}
