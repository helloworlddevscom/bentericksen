<?php

namespace App\Mail;

use App\PolicyUpdater;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PolicyUpdateEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Policy Updater object.
     * @var PolicyUpdater
     */
    public $policy_updater;

    /**
     * The status of the recipient (e.g., 'active', 'inactive', 'additional')
     * active/inactive returned from business status function  getStatusForUpdateEmails()
     * additional provided as separate string argument from additional email loop
     * @var string
     */
    public $recipient_status;

    /**
     * The core text which will be inserted into the email template.
     * Not to be confused with the fully rendered body.
     * @var string
     */
    public $email_body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PolicyUpdater $policy_updater, string $recipient_status)
    {
        $this->subject = $policy_updater->title;
        $this->recipient_status = $recipient_status;
        $this->policy_updater = $policy_updater;

        // build the body variable based on the recipient's status
        if ($recipient_status == 'active') {
            $this->email_body = $policy_updater->active_clients_text;
        } elseif ($recipient_status == 'inactive'){
            $this->email_body = $policy_updater->inactive_clients_text;
        } elseif ($recipient_status == 'additional') {
            // These are additional addresses which are specified in step 2 of the policy updater creation process
            // and do not have user accounts.
            $this->email_body = $policy_updater->additional_text;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.policyUpdate');
    }
}
