<?php

namespace App\Jobs;

use App\Business;
use App\Policy;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdatePolicies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $businessId;

    protected $fullRebuild;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($businessId, $fullRebuild = false)
    {
      Log::info('UpdatePolicies - updating policies for business '.$businessId);
      $this->businessId = $businessId;
      $this->fullRebuild = $fullRebuild;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      Log::info('UpdatePolicies - handling policy updates for business ' . $this->businessId);

      $business = Business::findOrFail($this->businessId);

      Log::info('UpdatePolicies - found business in lookup '.$business->name);
      
      if ($this->fullRebuild ) {
        Log::info('UpdatePolicies - full rebuild flag passed for ' . $this->businessId);
      }
      Log::info("UpdatePolicies - update policies now running for {$this->businessId}...");

      $business->updatePolicies($this->fullRebuild);

      Log::info('UpdatePolicies - update policies function ran for ' . $this->businessId);

      if ($this->fullRebuild) {
        Log::info('UpdatePolicies - full rebuild, resetting sort order for ' . $this->businessId);
        $this->restoreSorting($business);
      }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function restoreSorting(Business $business)
    {
      $policies = Policy::where('business_id', $business->id)
        ->where('status', '!=', 'closed')
        ->with('template')
        ->get();

      foreach ($policies as $policy) {
        if ($policy->template) {
          $policy->update(['order' => $policy->template->order]);
        }
      }
    }
}
