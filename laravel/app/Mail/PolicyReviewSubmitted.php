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
 * Class PolicyReviewSubmitted.
 *
 * Mailable class for rendering the "Policy Submitted for Review" email which is
 * sent to clients when an admin updates a policy
 */
class PolicyReviewSubmitted extends Mailable
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
    public $businessOwner;

    /**
     * The policy that was changed.
     * @var Policy
     */
    public $policy;

    /**
     * Create a new message instance.
     *
     * @param Authenticatable $user
     * @param Business $business
     * @param Policy $policy
     * @return void
     */
    public function __construct(Authenticatable $user, Policy $policy)
    {
        $this->subject = 'Policy Submitted for Review.';
        $this->user = $user;
        $this->policy = $policy;
        $this->business = $policy->business;
        $this->businessOwner = $this->business->getPrimaryUser(true);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.emails.business');
    }
}
