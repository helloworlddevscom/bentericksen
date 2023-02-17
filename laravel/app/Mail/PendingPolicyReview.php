<?php

namespace App\Mail;

use App\Business;
use App\Policy;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class PendingPolicyReview.
 *
 * Mailable class for rendering the "Pending Policy Review" email
 *
 * @see \App\Http\Controllers\User\PoliciesController
 */
class PendingPolicyReview extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user who made the change.
     * @var Authenticatable
     */
    public $user;

    /**
     * The user who made the change.
     * @var Business
     */
    public $business;

    /**
     * The owner of the business.
     * @var User
     */
    public $business_owner;

    /**
     * The policy that was changed.
     * @var Policy
     */
    public $policy;

    /**
     * Create a new message instance.
     *
     * @param Authenticatable $user
     * @param Policy $policy
     * @return void
     */
    public function __construct(Authenticatable $user, Policy $policy)
    {
        $this->subject = 'Pending Policy Review';
        $this->user = $user;
        $this->policy = $policy;
        $this->business = $policy->business;
        $this->business_owner = $this->business->getPrimaryUser(true);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.emails.consultant');
    }
}
