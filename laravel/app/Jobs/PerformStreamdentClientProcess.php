<?php

namespace App\Jobs;

use App\Business;
use App\Facades\StreamdentService;
use Bentericksen\StreamdentServices\StreamdentAPIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class PerformStreamdentClientProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Attempted job tries
     *
     * @var integer
     */
    public $tries = 40;

    /** @var Business */
    protected $business;

    /** @var string */
    protected $process;

    /**
     * Create a new job instance.
     *
     * @param Business $business
     * @param string $process
     */
    public function __construct(Business $business, string $process)
    {
        $this->business = $business;
        $this->process = $process;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch($this->process) {
            case "enable":
                $this->enableStreamdentClient($this->business);
                break;
            case "toggle":
                $this->toggleStreamdentClient($this->business);
                break;
            case "disable":
                $this->disableStreamdentClient($this->business);
                break;
        }
    }

    /**
     * Handle Job Failures
     *
     * @param [type] $exception
     * @return void
     */
    public function failure(\Exception $exception): void
    {
        if (strpos(strtolower($exception->getMessage()), "streamdent: name already exists") !== false) {
          //this re-triggers the business observer which then spawns another job and re-runs this logic. This runs in addition to the retry logic, which can create a mess.
          $this->business->update(['streamdent_name_increment' => ++$this->business->streamdent_name_increment]);
        }
        throw new \Exception($exception->getMessage());
    }

    /**
     * @param Business $business
     * @return void
     */
    public function createStreamdentClient(Business $business): void
    {
      Log::info('PerformStreamdentClientProcess - creating for business '.$business->id);

      try {
          $streamdentService = new StreamdentAPIService();
          $streamdentService->createClient([
              'business_id' => $business->id,
              'is_active' => 1
          ]);
      } catch(\Exception $e) {
          $this->failure($e);
      }
    }

    /**
     * Toggle SOPs for business
     * 
     * @param Business $business
     * @return void
     */
    public function toggleStreamdentClient(Business $business): void
    {
      Log::info('PerformStreamdentClientProcess - toggling for business '.$business->id);
      
      $client = $business->streamdentClient;

      if (!$business->sop_active && $client) {
        Log::info('PerformStreamdentClientProcess - disabling for business '.$business->id);
        $this->disableStreamdentClient($business);
        return;
      }

      $this->enableStreamdentClient($business);
    }

    /**
     * Disable SOPs for business
     * 
     * @param Business $business
     * @return void
     */
    public function disableStreamdentClient(Business $business): void
    {
      $client = $business->streamdentClient;

      if (!$client) {
        return;
      }

      try {
        $streamdentService = new StreamdentAPIService();
        $streamdentService->updateClient([
          'business_id' => $business->id,
          'is_active' => 0
        ]);
      } catch (\Exception $e) {
        $this->failure($e);
      }        
    }

    /**
     * Enable SOPs for business
     *
     * @param Business $business
     * @return void
     */
    protected function enableStreamdentClient(Business $business): void
    {
        $client = $business->streamdentClient;

        try {
          if (!$client) {
            Log::info('PerformStreamdentClientProcess - creating for business '.$business->id);
            $streamdentService = new StreamdentAPIService();
            $streamdentService->createClient([
                'business_id' => $business->id,
                'is_active' => 1
            ]);
          } else {
            Log::info('PerformStreamdentClientProcess - updating for business '.$business->id);
            $streamdentService = new StreamdentAPIService();
            $streamdentService->updateClient([
                'business_id' => $business->id,
                'is_active' => 1
            ]);
          }
        } catch (\Exception $e) {
          Log::info('PerformStreamdentClientProcess - error '.$e->getMessage());
          $this->failure($e);
        }
    }
}
