<?php

namespace App\Console\Commands;

use App\Business;
use App\OutgoingEmail;
use App\PolicyUpdater;
use App\PolicyUpdaterContact;
use App\Role;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PolicyUpdateEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'policyUpdateEmail {policy_updater_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails to those listed in the email list.';

    private $policy_updates;
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $policy_updater_id = $this->argument('policy_updater_id');
        if (is_null($policy_updater_id)) {
            $this->policy_updates = PolicyUpdater::all();
        } else {
            $this->policy_updates = PolicyUpdater::where('id', $policy_updater_id)->get();
        }

        foreach ($this->policy_updates as $key => $policy_update) {
            if (is_null($policy_update->start_date)) {
                continue;
            }

            $policy_start = new Carbon($policy_update->start_date);
            $policy_start = $policy_start->format('Y-m-d');
            $today = Carbon::now()->format('Y-m-d');

            if ($policy_start !== $today) {
                continue;
            }

            $primaryContacts = $policy_update->contacts()->primary();
            $additionalContacts = $policy_update->contacts()->additional();
            
            $closure = function($contact) use($policy_update) {
                $this->sendEmail($policy_update, $contact);
            };
            
            $closure->bindTo($this);
            
            // sending to users
            $primaryContacts->each($closure);

            // sending to additional email addresses that are not users
            $additionalContacts->each($closure);
        }
    }

    protected function sendEmail(
        PolicyUpdater $policy_update,
        PolicyUpdaterContact $contact
    )
    {
        if (empty($contact->user) && empty($contact->email)) {
            return;
        }
        $status = empty($contact->user) ? 'additional' : $contact->user->business->getStatusForUpdateEmails();

        $mailable = new \App\Mail\PolicyUpdateEmail($policy_update, $status);
        $mail = new OutgoingEmail([], $mailable);
        // since we're not sending to an actual user account, this email will tracked in the email log but not linked to any user account.
        if (empty($contact->user)) {
            $mail->to_address = $contact->email;
        } else {
            $mail->user_id = $contact->user->id;
        }
        
        $mail->related_type = PolicyUpdater::class;
        $mail->related_id = $policy_update->id;
        $mail->send();
    }
}
