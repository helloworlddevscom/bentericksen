<?php

namespace App\Console\Commands;

use App\Policy;
use Illuminate\Console\Command;
use App\Business;

class UpdateBusinessPolicyStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateBusinessPolicyStatus
                            {business_id : The ID of the Business}
                            {policy_id : The ID of the Business\' Policy}
                            {status : The desired Policy status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a Business Policy\'s status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $business_id = $this->argument('business_id');
        $policy_id = $this->argument('policy_id');
        $status = $this->argument('status');

        $business = Business::where('id', $business_id)->first();
        $policy = Policy::where('id', $policy_id)->first();

        $this->line('');
        $this->line(sprintf('Business: id - %u, name - %s', $business->id, $business->name));
        $this->line(sprintf('Policy: id - %u, manual name - %s', $policy->id, $policy->manual_name));

        if(!$this->confirm(
            sprintf('This will update the specified business policy\'s status from %s to %s.  Do you wish to continue?', $policy->status, $status))
        ) {
            return 0;
        }

        $policy->update(['status' => $status]);

        $this->line(sprintf('The policy status has been updated to %s', $policy->status));
        $this->line('');


    }
}
