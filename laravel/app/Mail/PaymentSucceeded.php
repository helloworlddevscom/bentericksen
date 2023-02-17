<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSucceeded extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @vars
     */
    public $event;
    public $subject = "HR Director Payment Successful";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.paymentSucceeded');
    }
}
