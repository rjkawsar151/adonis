<?php

namespace App\Mail;

use App\Models\MembershipRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MembershipRequestAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    public function __construct(MembershipRequest $request)
    {
        $this->request = $request;
    }

    public function build()
    {
        return $this->subject('[New VIP Request] Adonis Membership Registration')
                    ->view('emails.membership_request_admin');
    }
}
