<?php

namespace App\Jobs;

use Artisan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunPolicyUpdates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Artisan::call('policyTemplateUpdater');

        Artisan::call('userPolicyUpdates', [
            'policy_updater_id' => $this->id,
        ]);

        Artisan::call('policyUpdateEmail', [
            'policy_updater_id' => $this->id,
        ]);
    }
}
