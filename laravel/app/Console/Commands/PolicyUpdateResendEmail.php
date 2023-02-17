<?php

namespace App\Console\Commands;

use App\OutgoingEmail;
use App\PolicyUpdater;
use App\UserPolicyUpdates;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class PolicyUpdateResendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'policyUpdateResendEmail {policy_updater_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder email to users that have not accepted all policy updates one month after policy update effective date';

    private $policy_updates;

    protected const SKIPPED_MESSAGE = 'Pending Policy update emails disabled via configuration.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!config('policy.notifications.policyupdate')) {
            Log::info(self::SKIPPED_MESSAGE);
            $this->info(self::SKIPPED_MESSAGE);
            return;
        }
        

        $policy_updater_id = $this->argument('policy_updater_id');

        if (is_null($policy_updater_id)) {
            $lookupDate = new \Carbon\Carbon();
            $lookupDate = $lookupDate->subMonth();
            $this->policy_updates = PolicyUpdater::whereDate('start_date', $lookupDate)->get();
        } else {
            $this->policy_updates = PolicyUpdater::where('id', $policy_updater_id)->get();
        }

        foreach ($this->policy_updates as $key => $policy_update) {
            if (is_null($policy_update->start_date)) {
                continue;
            }

            $user_policy_updates = UserPolicyUpdates::where('policy_updater_id', $policy_update->id)
                ->where('accepted_at', '0000-00-00 00:00:00')
                ->groupBy('user_id')
                ->get();

            if (empty($user_policy_updates)) {
                continue;
            }

            foreach ($user_policy_updates as $user_policy_update) {
                if (empty($user_policy_update)) {
                    continue;
                }

                $this->sendEmail($user_policy_update);
            }
        }
    }

    public function sendEmail($policy_update)
    {
        $mailable = new \App\Mail\PolicyUpdateResendEmail();

        $mail = new OutgoingEmail([
            'user_id' => $policy_update->user->id,
            'related_type' => PolicyUpdater::class,
            'related_id' => $policy_update->policy_updater_id,
        ], $mailable);

        $mail->send();
    }
}
