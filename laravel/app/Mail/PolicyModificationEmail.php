<?php

namespace App\Mail;

use App\Policy;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class PolicyModificationEmail.
 *
 * Mailable class for rendering the "Policy Modification" email which is sent to clients
 * when an admin accepts or rejects a policy change made by the owner or manager
 */
class PolicyModificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The policy that was changed.
     * @var Policy
     */
    public $policy;

    /**
     * The decision, either 'approved' or 'rejected'.
     * @var string
     */
    public $decision;

    /**
     * The justification for rejecting a change.
     * @var string
     */
    public $justification;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Policy $policy, string $decision, string $justification = '')
    {
        $this->policy = $policy;
        $this->decision = $decision;
        $this->justification = $justification;
        $this->subject = 'Policy Modification - '.ucfirst($decision);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // there are 2 possible templates for this email, one for "approved" and one for "rejected"
        return $this->view('user.emails.policy.modification_'.$this->decision);
    }
}
