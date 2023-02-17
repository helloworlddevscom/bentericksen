<?php

namespace App\Mail;

use App\Business;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class PolicyModificationEmail.
 *
 * Mailable class for rendering the "Policy Modification" email which is sent to clients
 * when an admin accepts or rejects a policy change made by the owner or manager
 */
class AccountUpdateEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user that made the change.
     * @var User
     */
    public $user;

    /**
     * The business that was changed.
     * @var Business
     */
    public $business;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Business $business)
    {
        $this->user = $user;
        $this->business = $business;
        $this->subject = 'Business Account information change - '.$this->business->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // there are 2 possible templates for this email, one for "approved" and one for "rejected"
        return $this->view('user.emails.update');
    }
}
