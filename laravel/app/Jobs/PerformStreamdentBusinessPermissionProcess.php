<?php

namespace App\Jobs;

use App\BusinessPermission;
use App\Facades\StreamdentService;
use Bentericksen\StreamdentServices\StreamdentAPIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PerformStreamdentBusinessPermissionProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * Attempted job tries
     *
     * @var integer
     */
    public $tries = 20;

    /** @var BusinessPermission */
    protected $permission;

    /**
     * Create a new job instance.
     *
     * @param BusinessPermission $business
     */
    public function __construct(BusinessPermission $permission)
    {
      $this->permission = $permission;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $this->updateStreamdentBusinessPermission($this->permission);
    }

    /**
     * @param BusinessPermission $permission
     * @return void
     */
    private function updateStreamdentBusinessPermission(BusinessPermission $permission): void
    {
        if (!$permission->business->sop_active) {
          return;
        }

        $permission->business->hiredUsers->each(function ($user) use ($permission) {

            if (!$user->streamdentUser || !$user->hasRole('manager')) {
                return;
            }

            $streamdentService = new StreamdentAPIService();
            $streamdentService->updateUser([
              'user_id' => $user->id,
              'business_id' => $permission->business->id
            ]);
        });
    }
}
