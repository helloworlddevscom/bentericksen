<?php

namespace App\Mail;

use App\PolicyUpdater;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PolicyUpdateResendEmail extends Mailable
{
    public function __construct()
    {
        $this->subject = 'Pending Compliance Policy Changes';
    }

    /**
     * Build the PolicyUpdateResendEmail message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.emails.policy.reminder');
    }
}
