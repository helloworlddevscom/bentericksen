<?php

namespace App\Console\Commands;

use App\Business;
use App\PolicyTemplate;
use App\PolicyUpdater;
use App\User;
use Bentericksen\Policy\PolicyRules;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class UserPolicyUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userPolicyUpdates {policy_updater_id?} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create association within the database to state that 
    a certain user has, or has not, accepted all of their businesses policy updates';

    private $policy_updates;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $policy_updater_id = $this->argument('policy_updater_id');
        $force = $this->option('force');

        if (is_null($policy_updater_id)) {
            $this->policy_updates = PolicyUpdater::where('status', 'ready')->get();
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

            if ($policy_start !== $today && ! $force) {
                continue;
            }

            echo 'Processing PolicyUpdater #'.$policy_update->id.': '.$policy_update->title."\n";

            $emails = $policy_update->contacts()->primary()->pluck('email')->toArray();

            $additional_emails = $policy_update->contacts()->additional()->pluck('email')->toArray();

            foreach (array_merge($emails, $additional_emails) as $email) {
                $user = User::where('email', $email)->first();

                if (empty($user) || is_null($user->business_id)) {
                    continue;
                }

                $business = $user->business;

                $policy_update_policies = $this->parseUpdates($policy_update);

                $business_policies = $this->findBusinessPolicies($user->business, array_keys($policy_update_policies));

                if (empty($business_policies)) {
                    continue;
                }

                foreach ($business_policies as $business_policy) {
                    $user_policy_update = [];
                    $user_policy_update['policy_updater_id'] = $policy_update->id;
                    $user_policy_update['policy_template_update_id'] = $policy_update_policies[$business_policy];
                    $user_policy_update['user_id'] = $user->id;
                    $user_policy_update['policies'] = $business_policy;
                    $user_policy_update['created_at'] = Carbon::now();
                    $user_policy_update['accepted_at'] = '0000-00-00 00:00:00';
                    
                    DB::table('user_policy_updates')->insert($user_policy_update);
                }
            }

            $policy_update->status = 'complete';
            $policy_update->save();
        }
    }

    public function findBusinessPolicies($business, $policies)
    {
        $business_policies = [];

        foreach ($policies as $policy) {
            $template = PolicyTemplate::findOrFail($policy);

            $rules = new PolicyRules($business);

            if ($rules->all($template) === true) {
                $business_policies[] = $template->id;
            }
        }

        return $business_policies;
    }

    protected function parseUpdates($policy_update)
    {
        $policy_update_policies = (array) json_decode($policy_update->policies);

        $arr = [];
        array_walk($policy_update_policies, function ($value, $key) use (&$arr) {
            $arr[(int) $key] = (int) $value;
        });

        return $arr;
    }
}
