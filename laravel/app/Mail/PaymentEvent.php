<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

/**
 * Class PaymentEvent
 * @package App\Mail
 */
class PaymentEvent extends Mailable
{

    /**
     * @vars
     */
    public $notif_type;
    public $notif_data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($notif_type, $notif_data)
    {
        $this->notif_type = $notif_type;
        $this->notif_data = $notif_data;

        switch($notif_type) {

            case "payment_exception":
                $this->subject = "BE Payment Exception";
                break;
            case "payment_error":
                $this->subject = "BE Payment Error";
                break;
            case "payment_event":
                $this->subject = "BE Payment Event";
                break;
            default:
                $this->subject = "BE Payment Event";


        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from = env('SYSADMIN_FROM_ADDRESS');
        $fromName = env('SYSADMIN_FROM_NAME');

        return $this->subject($this->subject)
                    ->from($from, $fromName)
                    ->replyTo($from, $fromName)
                    ->text('emails.paymentEvent');
    }

}
